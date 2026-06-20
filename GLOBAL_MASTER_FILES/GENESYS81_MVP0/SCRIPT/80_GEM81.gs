/** 81+ MOTHERBOARD · file 80 · GEM81+ (scanner crypto. L'utente firma sempre. Nessuna esecuzione trade). */
function GEM_scanWatch(){
  // legge GEM81 (chain, token) e annota rischio. Solo informativo.
  var q=MB_read_(MB.SHEETS.GEM), n=0;
  q.forEach(function(r){ if(!r.token) return; n++; });
  MB_log_('GEM81','INFO','watch righe='+n+' (solo informativo, utente firma)'); return 'gem: '+n;
}
