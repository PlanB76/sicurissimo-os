/** 81+ MOTHERBOARD · file 90 · PVCORE81/PAYGATE81+ (wallet PV/PV+, 60/40, award). PV interni, mai denaro. */
function PVCORE_pushAwards(){
  var q=MB_read_(MB.SHEETS.AWARD_Q), done=0;
  q.forEach(function(r){ if(String(r.stato).toUpperCase()==='DONE') return; if(!r.sic_id||!r.pv) return;
    var res=MB_post_(MB.BASE.SFERA+'rewards/award.php',{sic_id:r.sic_id,pv:Number(r.pv),causale:r.causale||'award'});
    var ok=res.json&&res.json.ok;
    MB_ss_().getSheetByName(MB.SHEETS.AWARD_Q).getRange(r.__row,4).setValue(ok?'DONE':'ERR');
    if(ok){ MB_append_(MB.SHEETS.PVCORE,[MB_now_(),r.sic_id,0,Number(r.pv),r.causale||'award','queue']); done++; }
  });
  MB_log_('PVCORE81','INFO','PV+ award erogati='+done); return 'award: '+done;
}
function PVCORE_check6040(quotaCommunity){
  // verifica regola sostenibilita: community <= 60%
  var q=Number(quotaCommunity||0); var ok=q<=60;
  MB_log_('PVCORE81', ok?'INFO':'WARN','quota community '+q+'% '+(ok?'ok':'OLTRE 60% - blocco'));
  return ok;
}
