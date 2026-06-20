/** 81+ MOTHERBOARD · file 60 · CASHBACK81+ (benefit/voucher/ledger PV. Solo da ordini reali. PV interni, non denaro). */
function CASHBACK_processOrders(){
  // legge CASHBACK81 (ordini reali) e calcola PV+ benefit secondo regola; nessun denaro
  var q=MB_read_(MB.SHEETS.CASHBACK), n=0;
  q.forEach(function(r){ if(String(r.stato).toUpperCase()==='DONE') return; if(!r.ordine||!r.importo_pv) return;
    var pct=Number(r.regola===''?5:r.regola)||5; var pvplus=Math.round(Number(r.importo_pv)*pct/100);
    var sh=MB_ss_().getSheetByName(MB.SHEETS.CASHBACK);
    sh.getRange(r.__row,5).setValue(pvplus); sh.getRange(r.__row,6).setValue('DONE');
    if(r.sic_id){ MB_append_(MB.SHEETS.AWARD_Q,[r.sic_id,pvplus,'cashback ordine '+r.ordine,'NEW']); }
    n++; });
  MB_log_('CASHBACK81','INFO','ordini elaborati='+n); return 'cashback: '+n;
}
