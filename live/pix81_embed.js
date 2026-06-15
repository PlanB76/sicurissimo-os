/* 81+ MAPPA 3D EMBED. Incolla questo script in qualunque sito o sub-sito 81+.
   Uso: <script src="https://81plus.net/pix3d_embed.js" data-h="480"></script>
   Crea un iframe con la mappa 3D giocabile in modalita embed.
   Parametri opzionali sul tag script:
   data-h    altezza in pixel, predefinita 480
   data-base url base, predefinito https://81plus.net */
(function(){
  var me=document.currentScript||(function(){var s=document.getElementsByTagName('script');return s[s.length-1];})();
  var h=parseInt(me.getAttribute('data-h')||'480',10);
  var base=(me.getAttribute('data-base')||'https://81plus.net').replace(/\/$/,'');
  var wrap=document.createElement('div');
  wrap.style.cssText='position:relative;width:100%;max-width:1100px;margin:0 auto;border-radius:16px;overflow:hidden;border:1px solid #23232e;background:#05050a';
  var fr=document.createElement('iframe');
  fr.src=base+'/https://81plus.place?embed=1';
  fr.loading='lazy';
  fr.allow='fullscreen';
  fr.style.cssText='width:100%;height:'+h+'px;border:0;display:block;background:#05050a';
  fr.title='Mappa 3D dei 1000 PIX81+';
  var link=document.createElement('a');
  link.href=base+'/https://81plus.place';
  link.target='_blank';
  link.rel='noopener';
  link.textContent='Prendi il tuo PIX81+ →';
  link.style.cssText='position:absolute;right:12px;bottom:12px;background:linear-gradient(135deg,#E8501A,#FB6B00);color:#fff;font:700 13px Sora,Arial,sans-serif;padding:9px 18px;border-radius:10px;text-decoration:none;z-index:2';
  wrap.appendChild(fr);wrap.appendChild(link);
  me.parentNode.insertBefore(wrap,me.nextSibling);
})();
