/** 81+ MOTHERBOARD · file 10 · SCOUT81+ (acquisizione prospect/lead). Usa helper del CORE. */
function SCOUT_pull(){
  var all=[],cols=null,last=0,loops=0;
  while(loops<60){ loops++;
    var j=MB_json_(MB.BASE.SCOUT+'prospect_api.php?action=full_since&id='+last);
    if(!j||!j.ok){ MB_log_('SCOUT81','WARN','pull stop: '+(j&&j._raw||'no ok')); break; }
    if(!cols && j.cols && j.cols.length) cols=j.cols;
    if(!j.rows || !j.rows.length) break;
    j.rows.forEach(function(r){ all.push(r); }); last=j.last_id;
  }
  if(cols) MB_table_(MB.SHEETS.SCOUT, cols, all);
  MB_syncStatus_('SCOUT81','OK','righe='+all.length); MB_log_('SCOUT81','INFO','prospect='+all.length);
  return 'SCOUT prospect: '+all.length;
}
function SCOUT_scrapeTick(){
  var regs=['IT-25','IT-21','IT-34','IT-52','IT-62','IT-72','IT-57','IT-45','IT-42','IT-36'];
  var macros=['F','I','Q','C','G','S','N','M'];
  var k=Number(PropertiesService.getScriptProperties().getProperty('MB_ROT')||'0');
  var reg=regs[k%regs.length], macro=macros[k%macros.length];
  PropertiesService.getScriptProperties().setProperty('MB_ROT',String(k+1));
  MB_get_(MB.BASE.SCOUT+'collector.php?reg='+reg+'&macro='+macro);
  MB_log_('SCOUT81','INFO','scrape '+reg+'/'+macro); return reg+'/'+macro;
}
function SCOUT_enrichTick(){
  MB_get_(MB.BASE.SCOUT+'enrich.php?limit=400');
  MB_get_(MB.BASE.SCOUT+'webenrich.php?limit=40');
  MB_get_(MB.BASE.SCOUT+'ai_score.php?action=run&limit=40');
  MB_log_('SCOUT81','INFO','enrich+web+ai'); return 'enrich ok';
}
