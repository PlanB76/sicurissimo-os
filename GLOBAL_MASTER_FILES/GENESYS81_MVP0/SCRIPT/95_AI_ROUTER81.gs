/**
 * 81+ MOTHERBOARD · file 95 · AI ROUTER (chi-fa-cosa tra LLM, a runtime).
 * Sceglie il provider giusto per tipo di task, applica semantic guard e human approval.
 * Chiavi nelle Script Properties (NON nel foglio): OPENAI_API_KEY, ANTHROPIC_API_KEY, GEMINI_API_KEY, GROQ_API_KEY, OLLAMA_BRIDGE_URL.
 */
var AI_MAP = {
  // tipo task -> provider preferito (in ordine)
  CODICE:        ['ANTHROPIC'],            // Claude = architetto/builder
  DOCUMENTO:     ['ANTHROPIC'],
  STRATEGIA:     ['OPENAI','ANTHROPIC'],   // ChatGPT = stratega/copy
  COPY:          ['OPENAI'],
  SCRIPT:        ['OPENAI'],
  VISUAL:        ['GEMINI'],               // Gemini = immagini/asset
  SCORING:       ['GROQ'],                 // Groq = scoring veloce
  CLASSIFICA:    ['OLLAMA','GROQ'],        // gemma/gpt-oss locale
  NURTURE:       ['ANTHROPIC','GROQ'],     // engine runtime multi-canale
  DEFAULT:       ['GROQ','ANTHROPIC']
};
function AI_pick(tipo){ return (AI_MAP[String(tipo||'DEFAULT').toUpperCase()] || AI_MAP.DEFAULT); }

function AI_run(tipo, prompt, opts){
  opts=opts||{};
  var g=MB_semantic_(prompt);
  if(!g.clean){ MB_append_(MB.SHEETS.SEMANTIC,[MB_now_(),'AI_ROUTER',tipo,'BLOCCATO',g.terms.join(', '),String(prompt).slice(0,200),'human review']);
    return {ok:false, blocked:'semantic', terms:g.terms}; }
  if(MB_needsApproval_(opts.actionType)){
    MB_append_(MB.SHEETS.APPROVAL,[MB_now_(),opts.actionType,String(prompt).slice(0,120),'AI_ROUTER','IN ATTESA','','serve conferma umana']);
    return {ok:false, blocked:'human_approval', actionType:opts.actionType}; }
  var chain=AI_pick(tipo), used=null, out=null;
  for(var i=0;i<chain.length;i++){ var p=chain[i]; try{ out=AI_call_(p,prompt,opts); if(out){ used=p; break; } }catch(e){ MB_log_('AI_ROUTER','WARN',p+' fail: '+e); } }
  MB_append_(MB.SHEETS.AIBOARD,[MB_now_(),opts.task||tipo,used||'-',tipo,out?String(out).slice(0,180):'no provider','','DONE']);
  return {ok:!!out, provider:used, output:out};
}
function AI_call_(provider, prompt, opts){
  var P=PropertiesService.getScriptProperties();
  if(provider==='GROQ'){ var k=P.getProperty('GROQ_API_KEY'); if(!k) return null;
    var r=UrlFetchApp.fetch('https://api.groq.com/openai/v1/chat/completions',{method:'post',contentType:'application/json',muteHttpExceptions:true,
      headers:{Authorization:'Bearer '+k}, payload:JSON.stringify({model:'llama-3.3-70b-versatile',messages:[{role:'user',content:prompt}]})});
    var j=JSON.parse(r.getContentText()); return j.choices&&j.choices[0]&&j.choices[0].message.content; }
  if(provider==='ANTHROPIC'){ var ka=P.getProperty('ANTHROPIC_API_KEY'); if(!ka) return null;
    var ra=UrlFetchApp.fetch('https://api.anthropic.com/v1/messages',{method:'post',contentType:'application/json',muteHttpExceptions:true,
      headers:{'x-api-key':ka,'anthropic-version':'2023-06-01'}, payload:JSON.stringify({model:'claude-3-5-sonnet-latest',max_tokens:1024,messages:[{role:'user',content:prompt}]})});
    var ja=JSON.parse(ra.getContentText()); return ja.content&&ja.content[0]&&ja.content[0].text; }
  if(provider==='OPENAI'){ var ko=P.getProperty('OPENAI_API_KEY'); if(!ko) return null;
    var ro=UrlFetchApp.fetch('https://api.openai.com/v1/chat/completions',{method:'post',contentType:'application/json',muteHttpExceptions:true,
      headers:{Authorization:'Bearer '+ko}, payload:JSON.stringify({model:'gpt-4.1-mini',messages:[{role:'user',content:prompt}]})});
    var jo=JSON.parse(ro.getContentText()); return jo.choices&&jo.choices[0]&&jo.choices[0].message.content; }
  if(provider==='GEMINI'){ var kg=P.getProperty('GEMINI_API_KEY'); if(!kg) return null;
    var rg=UrlFetchApp.fetch('https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key='+kg,
      {method:'post',contentType:'application/json',muteHttpExceptions:true, payload:JSON.stringify({contents:[{parts:[{text:prompt}]}]})});
    var jg=JSON.parse(rg.getContentText()); return jg.candidates&&jg.candidates[0]&&jg.candidates[0].content.parts[0].text; }
  if(provider==='OLLAMA'){ var url=P.getProperty('OLLAMA_BRIDGE_URL'); if(!url) return null;
    var rl=UrlFetchApp.fetch(url,{method:'post',contentType:'application/json',muteHttpExceptions:true,
      payload:JSON.stringify({model:'gemma3:4b',prompt:prompt})});
    try{ return JSON.parse(rl.getContentText()).response; }catch(e){ return rl.getContentText().slice(0,500); } }
  return null;
}
