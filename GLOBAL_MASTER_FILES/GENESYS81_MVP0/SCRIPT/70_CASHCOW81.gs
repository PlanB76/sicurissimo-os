/** 81+ MOTHERBOARD · file 70 · CASHCOW81+ (YouTube/contenuti -> acquisizione). MVP0: registro + KPI. */
function CASHCOW_log(contenuto,canale,kpi){
  MB_append_(MB.SHEETS.CASHCOW,[MB_now_(),contenuto||'',canale||'YouTube','PUBBLICATO',kpi||'']);
  MB_log_('CASHCOW81','INFO','log contenuto '+(contenuto||'')); return 'ok';
}
function CASHCOW_pull(){ MB_log_('CASHCOW81','INFO','CASHCOW pull placeholder (YouTube Data API da collegare)'); return 'placeholder'; }
