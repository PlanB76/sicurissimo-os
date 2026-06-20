/** 81+ MOTHERBOARD · file 30 · LEX81+ (normativo: sicurezza/HACCP/privacy). Valori senza fonte = DA_VERIFICARE. */
function LEX_pull(){
  var lista=MB.ATECO_DEFAULT;
  MB_read_(MB.SHEETS.CONFIG).forEach(function(r){ if(r.chiave==='ateco_lista'&&r.valore) lista=String(r.valore).split(',').map(function(s){return s.trim();}); });
  var rows=[];
  lista.forEach(function(at){
    var j=MB_json_(MB.BASE.LEX+'lex81.php?action=obligations&ateco_code='+encodeURIComponent(at));
    if(j && j.obblighi) j.obblighi.forEach(function(o){ rows.push([at,o.tema||'',o.obbligo||o.titolo||'',o.stato||'DA_VERIFICARE']); });
  });
  if(rows.length) MB_table_(MB.SHEETS.LEX,['ateco','tema','obbligo','stato'],rows);
  MB_syncStatus_('LEX81','OK','obblighi='+rows.length); MB_log_('LEX81','INFO','obblighi='+rows.length);
  return 'LEX obblighi: '+rows.length;
}
function LEX_audit(ateco){
  var j=MB_json_(MB.BASE.LEX+'lex81.php?action=audit&ateco_code='+encodeURIComponent(ateco||'41'));
  MB_log_('LEX81','INFO','audit '+ateco); return j;
}
