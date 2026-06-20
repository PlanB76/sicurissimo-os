/** 81+ MOTHERBOARD · file 20 · LEADGEN81+ (nurturing etico). Nessun invio senza doppio opt-in. */
function LEADGEN_import(){
  var j=MB_json_(MB.BASE.LEXROOT+'import_from_scout.php?limit=200');
  MB_syncStatus_('LEADGEN81','OK',JSON.stringify(j).slice(0,120));
  MB_log_('LEADGEN81','INFO','import '+JSON.stringify(j).slice(0,120));
  return 'LEADGEN import: '+(j&&(j.importati||j.count||j.ok));
}
function LEADGEN_startFlows(){
  // legge LEADGEN_QUEUE; start_flow rispetta il doppio opt-in lato server (consenso confermato)
  var q=MB_read_(MB.SHEETS.LEADGEN), n=0;
  q.forEach(function(r){ if(!r.email || !r.flow) return;
    MB_post_(MB.BASE.LEX+'leadgen.php?action=start_flow',{email:r.email,flow:r.flow}); n++; });
  MB_log_('LEADGEN81','INFO','flow avviati (solo se consenso): '+n); return 'flow: '+n;
}
