// KIT 81+ . inietta header, footer, cielo stellato e brillanti su ogni sub progetto.
(function(){
 var P=window.K81||{}; var nome=P.nome||'81+'; var hub=P.hub||'HUB1';
 var head=document.createElement('div');
 head.innerHTML='<header class="k-header"><a class="k-logo" href="https://81plus.net"><span class="met">81+</span></a>'
  +'<span style="font-family:Anton,Arial;font-size:13px;letter-spacing:2px;color:#9aa0b4;text-transform:uppercase">'+nome+'</span>'
  +'<nav class="k-nav"><a href="https://81plus.net">HUB1 Identità</a><a href="https://sicurissimo.online">HUB2 Economia</a><a href="https://81plus.online">HUB3 Web3</a><a href="https://81plus.net/dashboard.html">Entra col SIC ID</a></nav></header>';
 document.body.prepend(head.firstChild);
 var foot=document.createElement('footer'); foot.className='k-footer';
 foot.innerHTML='Ecosistema 81+ · Labo Tecnic Studio · P.IVA IT01504180298 · '+hub
  +'<br><a href="https://81plus.net">81plus.net</a><a href="https://sicurissimo.online">sicurissimo.online</a><a href="https://81plus.online">81plus.online</a><a href="https://wa.me/393388771737">WhatsApp la direzione</a>'
  +'<br>Un solo SIC ID per tutto l\'ecosistema. I dati di mercato citati sono INAIL, INL e ISTAT.';
 document.body.appendChild(foot);
 var c=document.createElement('canvas'); c.id='stelle'; document.body.prepend(c);
 var x=c.getContext('2d'),S=[];
 function dim(){c.width=innerWidth;c.height=innerHeight;}
 dim(); addEventListener('resize',dim);
 for(var i=0;i<120;i++)S.push({x:Math.random(),y:Math.random(),r:Math.random()*1.4+.3,v:Math.random()*.0005+.0001,a:Math.random()});
 (function anima(){
   x.clearRect(0,0,c.width,c.height);
   S.forEach(function(s){ s.y+=s.v; if(s.y>1)s.y=0; s.a+=.02;
     x.globalAlpha=.35+.3*Math.sin(s.a); x.fillStyle=Math.random()<.02?'#ffb38a':'#fff';
     x.beginPath(); x.arc(s.x*c.width,s.y*c.height,s.r,0,7); x.fill(); });
   requestAnimationFrame(anima);
 })();
 document.querySelectorAll('.k-card').forEach(function(card){
   for(var i=0;i<2;i++){ var b=document.createElement('span'); b.className='k-brill';
     b.style.left=(10+Math.random()*80)+'%'; b.style.top=(10+Math.random()*70)+'%';
     b.style.animationDelay=(Math.random()*3)+'s'; card.appendChild(b); }
 });
})();
