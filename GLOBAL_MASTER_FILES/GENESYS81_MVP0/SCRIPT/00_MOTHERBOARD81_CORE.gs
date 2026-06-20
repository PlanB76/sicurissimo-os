/**
 * ============================================================
 * 81+ MASTERBLASTER · MOTHERBOARD · CORE  (file 00)
 * MVP0 · PLANB.CASH LTD · 81+ GLOBAL OS / SICURISSIMO
 *
 * Questo file CREA il foglio Master Blaster e governa tutto.
 * Incolla TUTTI i file 00..95 nello stesso progetto Apps Script (uno per singolarita).
 * Un solo onOpen e un solo install vivono qui.
 *
 * SEGRETI (mai nel foglio): Impostazioni progetto > Proprieta script:
 *   X81_SECRET        secret admin endpoint PHP (oggi SCOUT81-OWNER-2026, da cambiare)
 *   MB81_WEBTOKEN     token Web App doPost (consigliato)
 *   (facoltativi AI)  OPENAI_API_KEY · ANTHROPIC_API_KEY · GEMINI_API_KEY · GROQ_API_KEY · OLLAMA_BRIDGE_URL
 *
 * AVVIO: menu "81+ MOTHERBOARD" > Installa motherboard  (oppure MB_install)
 * ============================================================
 */

var MB = {
  VERSION: 'MVP0-1.0',
  TZ: 'Europe/Rome',
  BASE: {
    SCOUT:  'https://81plus.net/admin/scout81/',
    SFERA:  'https://81plus.net/admin/sfera81/api/',
    SFROOT: 'https://81plus.net/admin/sfera81/',
    LEX:    'https://81plus.net/admin/lex81/api/',
    LEXROOT:'https://81plus.net/admin/lex81/'
  },
  SHEETS: {
    DASH:'DASHBOARD', CONFIG:'CONFIG', KPI:'KPI81', LOG:'LOG81', TASKS:'TASKS81', COMMANDS:'COMMANDS',
    AIBOARD:'AI_BOARD', SYNC:'SYNC_STATUS', SEMANTIC:'SEMANTIC_GUARD_LOG', APPROVAL:'HUMAN_APPROVAL',
    MODULES:'MODULES81', ROADMAP:'ROADMAP81', SECURITY:'SECURITY_AUDIT',
    SCOUT:'SCOUT81_PROSPECTS', MISSIONI:'GIOCHI_MISSIONI', GIOCHI:'GIOCHI_STATO',
    AWARD_Q:'PVPLUS_AWARD_QUEUE', LEX:'LEX_OBBLIGHI', LEADGEN:'LEADGEN_QUEUE',
    CASHBACK:'CASHBACK81', CASHCOW:'CASHCOW81', GEM:'GEM81', PVCORE:'PVCORE_LEDGER'
  },
  MODULES: ['SCOUT81','LEADGEN81','LEX81','SFERA81','GIOCHI81','GAMIFICATION81','CASHBACK81','CASHCOW81','GEM81','PVCORE81'],
  FORBIDDEN: ['investimento','rendimento','rendita','roi','apy','staking','interesse','capitale',
              'guadagno garantito','liquidita garantita','rendita passiva','rischio zero','zero multe',
              'garantito 100','soldi facili','schema piramidale'],
  HUMAN_APPROVAL: ['SEND_EMAIL','PUBLISH','PAYOUT','SETTLEMENT','CONTRACT','LEGAL_CLAIM','NORMATIVE_CLAIM','DELETE','PAYMENT','TRADE','WEB3_DEPLOY'],
  ATECO_DEFAULT: ['41','43','56','86','25','10']
};

/* ---------- helpers ---------- */
function MB_secret_(){ return PropertiesService.getScriptProperties().getProperty('X81_SECRET') || 'SCOUT81-OWNER-2026'; }
function MB_now_(){ return Utilities.formatDate(new Date(), MB.TZ, 'yyyy-MM-dd HH:mm:ss'); }
function MB_ss_(){ return SpreadsheetApp.getActiveSpreadsheet(); }
function MB_sh_(n){ var ss=MB_ss_(); return ss.getSheetByName(n) || ss.insertSheet(n); }
function MB_sep_(u){ return u.indexOf('?')>=0?'&':'?'; }
function MB_u_(u){ return u + MB_sep_(u) + 'secret=' + encodeURIComponent(MB_secret_()); }
function MB_get_(u){ try{ var r=UrlFetchApp.fetch(MB_u_(u),{muteHttpExceptions:true,followRedirects:true,
    headers:{'X-81PLUS-SECRET':MB_secret_(),'User-Agent':'Mozilla/5.0 MB81-MOTHERBOARD'}});
    return {code:r.getResponseCode(),text:r.getContentText()}; }catch(e){ return {code:0,text:String(e)}; } }
function MB_json_(u){ var r=MB_get_(u); try{ return JSON.parse(r.text); }catch(e){ return {ok:false,_code:r.code,_raw:String(r.text).slice(0,200)}; } }
function MB_post_(u,p){ try{ var r=UrlFetchApp.fetch(MB_u_(u),{method:'post',contentType:'application/json',
    muteHttpExceptions:true,headers:{'X-81PLUS-SECRET':MB_secret_()},payload:JSON.stringify(p||{})});
    var t=r.getContentText(),j; try{j=JSON.parse(t);}catch(e){j={ok:false,_raw:t.slice(0,200)};}
    return {code:r.getResponseCode(),json:j}; }catch(e){ return {code:0,json:{ok:false,_err:String(e)}}; } }
function MB_table_(n,h,rows){ var sh=MB_sh_(n); sh.clearContents();
    sh.getRange(1,1,1,h.length).setValues([h]); if(rows&&rows.length) sh.getRange(2,1,rows.length,h.length).setValues(rows);
    try{sh.setFrozenRows(1);}catch(e){} return rows?rows.length:0; }
function MB_read_(n){ var sh=MB_ss_().getSheetByName(n); if(!sh) return []; var v=sh.getDataRange().getValues();
    if(v.length<2) return []; var h=v[0],o=[]; for(var i=1;i<v.length;i++){var r={};for(var c=0;c<h.length;c++)r[h[c]]=v[i][c];r.__row=i+1;o.push(r);} return o; }
function MB_log_(mod,lvl,msg){ var sh=MB_sh_(MB.SHEETS.LOG); if(sh.getLastRow()===0) sh.appendRow(['ts','modulo','livello','messaggio']);
    sh.appendRow([MB_now_(),mod,lvl,String(msg).slice(0,800)]); }

/* ---------- semantic guard + human approval ---------- */
function MB_semantic_(text){ var t=String(text||'').toLowerCase(), hit=[];
    MB.FORBIDDEN.forEach(function(w){ if(t.indexOf(w)>=0) hit.push(w); }); return {clean:hit.length===0, terms:hit}; }
function MB_needsApproval_(type){ return MB.HUMAN_APPROVAL.indexOf(String(type||'').toUpperCase())>=0; }

/* ---------- menu + install ---------- */
function onOpen(){
  SpreadsheetApp.getUi().createMenu('81+ MOTHERBOARD')
    .addItem('Installa motherboard (tab+trigger)','MB_install')
    .addSeparator()
    .addItem('RUN ALL adesso','MB_runAll')
    .addItem('Sync SCOUT81+','SCOUT_pull')
    .addItem('Sync GIOCHI81+','GIOCHI_pull')
    .addItem('Sync LEX81+','LEX_pull')
    .addItem('Importa LEADGEN da SCOUT','LEADGEN_import')
    .addItem('PV+ award in coda','PVCORE_pushAwards')
    .addSeparator()
    .addItem('Semantic guard scan TASKS','MB_scanSemantic')
    .addItem('Snapshot KPI','MB_snapshotKpi')
    .addItem('Report giornaliero','MB_dailyReport')
    .addToUi();
}
function MB_install(){
  MB_buildSheets_(); MB_seedConfig_(); MB_seedModules_(); MB_seedRoadmap_(); MB_seedAiBoard_();
  MB_installTriggers_();
  MB_log_('CORE','INFO','motherboard installato v'+MB.VERSION);
  try{ SpreadsheetApp.getUi().alert('81+ MOTHERBOARD installato. Tab create, trigger attivi.'); }catch(e){}
  return 'OK';
}
function MB_trig_(fn,everyMin,dailyHour){
  ScriptApp.getProjectTriggers().forEach(function(t){ if(t.getHandlerFunction()===fn) ScriptApp.deleteTrigger(t); });
  if(everyMin){ ScriptApp.newTrigger(fn).timeBased().everyMinutes(everyMin).create(); return; }
  if(dailyHour!=null){ ScriptApp.newTrigger(fn).timeBased().everyDays(1).atHour(dailyHour).create(); }
}
function MB_installTriggers_(){
  MB_trig_('MB_runAll',15); MB_trig_('SCOUT_pull',10); MB_trig_('SCOUT_scrapeTick',20);
  MB_trig_('GIOCHI_pull',60); MB_trig_('PVCORE_pushAwards',20);
  MB_trig_('LEX_pull',null,7); MB_trig_('LEADGEN_import',null,8); MB_trig_('MB_dailyReport',null,9);
}

/* ---------- sezioni ---------- */
function MB_buildSheets_(){
  var d=MB_sh_(MB.SHEETS.DASH); d.clear(); d.setTabColor('#ff7a00');
  d.getRange(1,1,8,4).setValues([
    ['81+ MASTERBLASTER · MOTHERBOARD','','',''],
    ['Nodo centrale Sheet <-> DB u173050672_81plusglobal · MVP0','','',''],
    ['CTA unica','81plus.net','Versione',MB.VERSION],
    ['Regola','PV/PV+ utility, mai denaro · semantic guard · doppio opt-in · human approval','',''],
    ['','','',''],
    ['KPI','VALORE','AGGIORNATO','NOTE'],
    ['Prospect SCOUT','=IFERROR(COUNTA(\''+MB.SHEETS.SCOUT+'\'!A2:A),0)','','sync auto'],
    ['Obblighi LEX','=IFERROR(COUNTA(\''+MB.SHEETS.LEX+'\'!A2:A),0)','','sync auto']
  ]);
  MB_table_(MB.SHEETS.COMMANDS,['cmd_id','ts','origine','hub','priorita','titolo','comando','final_url','stato'],[]);
  MB_table_(MB.SHEETS.TASKS,['task_id','ts','updated','origine','modulo','priorita','stato','titolo','prompt','agente','approvato','final_url'],[]);
  MB_table_(MB.SHEETS.AIBOARD,['ts','task','provider','ruolo','esito','note','stato'],[]);
  MB_table_(MB.SHEETS.SYNC,['modulo','last_run','last_ok','errore','stato'],[]);
  MB_table_(MB.SHEETS.SEMANTIC,['ts','origine','dove','stato','termini','estratto','azione'],[]);
  MB_table_(MB.SHEETS.APPROVAL,['ts','tipo','oggetto','richiedente','stato','approvato_da','note'],[]);
  MB_table_(MB.SHEETS.SECURITY,['ts','check','esito','note'],[]);
  MB_table_(MB.SHEETS.AWARD_Q,['sic_id','pv','causale','stato'],[]);
  MB_table_(MB.SHEETS.MISSIONI,['id','pilastro','area','pv','stato'],[]);
  MB_table_(MB.SHEETS.LEX,['ateco','tema','obbligo','stato'],[]);
  MB_table_(MB.SHEETS.LEADGEN,['email','nome','ateco','flow','consenso'],[]);
  MB_table_(MB.SHEETS.CASHBACK,['ordine','sic_id','importo_pv','regola','pvplus','stato'],[]);
  MB_table_(MB.SHEETS.CASHCOW,['data','contenuto','canale','stato','kpi'],[]);
  MB_table_(MB.SHEETS.GEM,['chain','token','rischio','nota','ts'],[]);
  MB_table_(MB.SHEETS.PVCORE,['ts','sic_id','delta_pv','delta_pvplus','causale','ref'],[]);
  if(!MB_ss_().getSheetByName(MB.SHEETS.LOG)) MB_sh_(MB.SHEETS.LOG).appendRow(['ts','modulo','livello','messaggio']);
  if(!MB_ss_().getSheetByName(MB.SHEETS.KPI)) MB_sh_(MB.SHEETS.KPI).appendRow(['data','prospect','obblighi_lex','missioni','note']);
}
function MB_seedConfig_(){
  MB_table_(MB.SHEETS.CONFIG,['chiave','valore','tipo','area','nota'],[
    ['CTA_UNICA','81plus.net','TEXT','MARKETING','CTA unica'],
    ['DB_SOURCE_OF_TRUTH','u173050672_81plusglobal','TEXT','DB','DB = fonte di verita'],
    ['SEMANTIC_GUARD','TRUE','BOOL','COMPLIANCE','parole vietate attive'],
    ['DOUBLE_OPTIN','TRUE','BOOL','LEADGEN81','nessuna email senza doppio opt-in'],
    ['HUMAN_APPROVAL','TRUE','BOOL','CORE','azioni critiche con conferma umana'],
    ['ateco_lista',MB.ATECO_DEFAULT.join(','),'LIST','LEX81','ATECO per audit'],
    ['base_scout',MB.BASE.SCOUT,'URL','SCOUT81',''],
    ['base_sfera',MB.BASE.SFERA,'URL','SFERA81',''],
    ['base_lex',MB.BASE.LEX,'URL','LEX81',''],
    ['secret','(Script Properties X81_SECRET)','SECRET_REF','SECURITY','mai nel foglio']
  ]);
}
function MB_seedModules_(){
  var rows=[
    ['SCOUT81','Acquisizione prospect/lead','/admin/scout81/','file 10','ON'],
    ['LEADGEN81','Nurturing etico + doppio opt-in','/admin/lex81/','file 20','ON'],
    ['LEX81','Normativo: sicurezza/HACCP/privacy','/admin/lex81/','file 30','ON'],
    ['SFERA81','Gamification ATECO/RISCHIO + PV+','/admin/sfera81/','file 40','ON'],
    ['GIOCHI81','8 giochi 3D + classifiche','/admin/sfera81/giochi/','file 40','ON'],
    ['GAMIFICATION81','Status/badge/leaderboard','sheet','file 50','ON'],
    ['CASHBACK81','Benefit/voucher/ledger PV','sheet','file 60','ON'],
    ['CASHCOW81','YouTube/contenuti acquisizione','sheet','file 70','PARZIALE'],
    ['GEM81','Scanner crypto (utente firma)','/admin/gem81/','file 80','OPZIONALE'],
    ['PVCORE81','Wallet PV/PV+ · 60/40 · PayGate','/admin/scout81/plp_api.php','file 90','ON']
  ];
  MB_table_(MB.SHEETS.MODULES,['modulo','funzione','collocazione','script','stato'],rows);
}
function MB_seedRoadmap_(){
  MB_table_(MB.SHEETS.ROADMAP,['fase','giorni','output','stato'],[
    ['MVP0 wave1','1-7','Landing HUB1, audit gratuito, CRM, PDF magnete, email, admin dashboard','IN CORSO'],
    ['MVP0 wave2','8-14','SIC-ID, wallet PV+, missione, Ruota/Piramide, Networker area, Scout pack manuale','DA FARE'],
    ['MVP0 wave3','15-30','Pass/Kit, Scout semiautomatico, PV+ Exchange limitato, content engine, KPI','DA FARE'],
    ['MVP1','31-60','CAREER81+ base, EQUILIBRIUM EQ1-3, gamification, territory, funding, welfare','DA FARE'],
    ['MVP1','61-90','Orchestrazione agenti AI, scoring avanzato, POINT81+ portal, DAO MVP','DA FARE']
  ]);
}
function MB_seedAiBoard_(){
  var sh=MB_sh_(MB.SHEETS.AIBOARD);
  // intestazione gia messa in buildSheets; qui aggiungiamo una riga doc dei ruoli
  MB_append_(MB.SHEETS.AIBOARD,[MB_now_(),'-','CLAUDE','Architetto/Builder','codice·documenti·sistemi·QA','grado 4','DOC']);
  MB_append_(MB.SHEETS.AIBOARD,[MB_now_(),'-','CHATGPT','Stratega/Copy','master prompt·brainstorming·copy·script','grado 4','DOC']);
  MB_append_(MB.SHEETS.AIBOARD,[MB_now_(),'-','GEMINI','Visual/Designer','immagini ufficiali·asset brand·slide','grado 1','DOC']);
  MB_append_(MB.SHEETS.AIBOARD,[MB_now_(),'-','CLAUDE_API','Engine runtime','nurture·routing·risposte multi-canale','runtime','DOC']);
  MB_append_(MB.SHEETS.AIBOARD,[MB_now_(),'-','GROQ','Scoring veloce','conversione A/B/C prospect','runtime','DOC']);
  MB_append_(MB.SHEETS.AIBOARD,[MB_now_(),'-','GEMMA+GPTOSS','Autopilota locale','classificazione·lavori massivi offline','local','DOC']);
}
function MB_append_(n,row){ MB_sh_(n).appendRow(row); }

/* ---------- run all / orchestrazione ---------- */
function MB_runAll(){
  try{
    MB_processCommands_(); MB_scanSemantic(); MB_syncAll_(); MB_snapshotKpi();
    MB_log_('CORE','INFO','RUN ALL ok');
  }catch(e){ MB_log_('CORE','ERROR','RUN ALL fail: '+e); }
  return 'RUN ALL';
}
function MB_processCommands_(){
  var cmds=MB_read_(MB.SHEETS.COMMANDS), n=0;
  cmds.forEach(function(c){
    if(String(c.stato).toUpperCase()==='DONE') return;
    if(!c.comando) return;
    MB_append_(MB.SHEETS.TASKS,[c.cmd_id||('CMD'+c.__row),MB_now_(),MB_now_(),c.origine||'COMMANDS',c.hub||'',c.priorita||'NORMAL','NEW',c.titolo||'',c.comando,'ORCHESTRATOR81','NO',c.final_url||'']);
    MB_ss_().getSheetByName(MB.SHEETS.COMMANDS).getRange(c.__row,9).setValue('DONE'); n++;
  });
  if(n) MB_log_('CORE','INFO','comandi -> task: '+n);
}
function MB_scanSemantic(){
  var tasks=MB_read_(MB.SHEETS.TASKS), flagged=0;
  tasks.forEach(function(t){
    var g=MB_semantic_((t.titolo||'')+' '+(t.prompt||''));
    if(!g.clean){ MB_append_(MB.SHEETS.SEMANTIC,[MB_now_(),'TASKS81','riga '+t.__row,'REVIEW',g.terms.join(', '),String(t.prompt).slice(0,200),'human review']);
      MB_ss_().getSheetByName(MB.SHEETS.TASKS).getRange(t.__row,7).setValue('BLOCCATO_SEMANTIC'); flagged++; }
  });
  if(flagged) MB_log_('COMPLIANCE','WARN','task bloccati da semantic guard: '+flagged);
  return 'semantic scan: '+flagged;
}
function MB_syncAll_(){
  try{ SCOUT_pull(); }catch(e){ MB_syncStatus_('SCOUT81','ERR',e); }
  try{ GIOCHI_pull(); }catch(e){ MB_syncStatus_('GIOCHI81','ERR',e); }
  try{ LEX_pull(); }catch(e){ MB_syncStatus_('LEX81','ERR',e); }
}
function MB_syncStatus_(mod,stato,info){
  var sh=MB_sh_(MB.SHEETS.SYNC); var rows=MB_read_(MB.SHEETS.SYNC); var found=null;
  rows.forEach(function(r){ if(r.modulo===mod) found=r.__row; });
  var row=[mod,MB_now_(),stato==='OK'?MB_now_():'',stato==='OK'?'':String(info).slice(0,300),stato];
  if(found) sh.getRange(found,1,1,row.length).setValues([row]); else MB_append_(MB.SHEETS.SYNC,row);
}
function MB_snapshotKpi(){
  var prospect=''; var s=MB_json_(MB.BASE.SCOUT+'prospect_api.php?action=stats'); if(s&&s.ok) prospect=(s.totale||s.total||'');
  var obl=MB_read_(MB.SHEETS.LEX).length, mis=MB_read_(MB.SHEETS.MISSIONI).length;
  var k=MB_sh_(MB.SHEETS.KPI); if(k.getLastRow()===0) k.appendRow(['data','prospect','obblighi_lex','missioni','note']);
  k.appendRow([MB_now_(),prospect,obl,mis,'snapshot']); return 'KPI ok';
}
function MB_dailyReport(){ MB_snapshotKpi(); MB_log_('CORE','INFO','daily report'); return 'daily ok'; }

/* ---------- WEB APP: punto unico per le AI esterne (gemma, gpt-oss, n8n) ---------- */
function doPost(e){
  try{
    var b=e&&e.postData?JSON.parse(e.postData.contents||'{}'):{};
    var tok=PropertiesService.getScriptProperties().getProperty('MB81_WEBTOKEN')||'';
    if(tok && b.token!==tok) return MB_out_({ok:false,err:'token'});
    var g=MB_semantic_(JSON.stringify(b.payload||b));
    var sh=MB_sh_(MB.SHEETS.COMMANDS); if(sh.getLastRow()===0) sh.appendRow(['cmd_id','ts','origine','hub','priorita','titolo','comando','final_url','stato']);
    sh.appendRow(['WEB'+Date.now(),MB_now_(),b.origine||'AI',b.hub||'',b.priorita||'NORMAL',b.titolo||'',JSON.stringify(b.payload||{}),'',g.clean?'NEW':'BLOCCATO_SEMANTIC']);
    return MB_out_({ok:true, semantic:g});
  }catch(err){ return MB_out_({ok:false,err:String(err)}); }
}
function MB_out_(o){ return ContentService.createTextOutput(JSON.stringify(o)).setMimeType(ContentService.MimeType.JSON); }
