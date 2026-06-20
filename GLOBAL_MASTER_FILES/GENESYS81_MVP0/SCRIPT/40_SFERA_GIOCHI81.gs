/** 81+ MOTHERBOARD · file 40 · SFERA81+/GIOCHI81+ (gamification azione-driven, PV+ reali). */
function GIOCHI_pull(){
  var miss=MB_json_(MB.BASE.SFERA+'missions/list.php'), rows=[];
  if(miss && miss.missioni) miss.missioni.forEach(function(m){ rows.push([m.id,m.pilastro||'',m.area||'',m.pv||'',m.stato||'']); });
  if(rows.length) MB_table_(MB.SHEETS.MISSIONI,['id','pilastro','area','pv','stato'],rows);
  var esc=MB_json_(MB.BASE.SFERA+'escalation/state.php'), lw=MB_json_(MB.BASE.SFERA+'lifewheel/state.php');
  var g=MB_sh_(MB.SHEETS.GIOCHI); g.clearContents(); g.getRange(1,1,1,3).setValues([['gioco','chiave','valore']]);
  g.getRange(2,1,2,3).setValues([['escalation','raw',JSON.stringify(esc).slice(0,400)],['lifewheel','raw',JSON.stringify(lw).slice(0,400)]]);
  MB_syncStatus_('GIOCHI81','OK','missioni='+rows.length); MB_log_('GIOCHI81','INFO','missioni='+rows.length);
  return 'GIOCHI missioni: '+rows.length;
}
