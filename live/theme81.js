// 81+ campo stellato leggero, canvas unico, rispetta prefers-reduced-motion
(function(){
 if(window.__t81)return;window.__t81=1;
 var rm=window.matchMedia&&matchMedia('(prefers-reduced-motion: reduce)').matches;
 var c=document.createElement('canvas');c.id='t81stars';document.body.prepend(c);
 var x=c.getContext('2d'),W,H,S=[];
 function rs(){W=c.width=innerWidth;H=c.height=innerHeight;}
 rs();addEventListener('resize',rs);
 var N=Math.min(140,Math.floor(W*H/14000));
 for(var i=0;i<N;i++)S.push({x:Math.random()*W,y:Math.random()*H,r:Math.random()*1.4+.3,a:Math.random(),v:(Math.random()*.0125+.004)*(Math.random()<.5?-1:1),dx:(Math.random()-.5)*.05,dy:Math.random()*.05+.01});
 function tick(){x.clearRect(0,0,W,H);
  for(var i=0;i<S.length;i++){var s=S[i];
   s.a+=s.v;if(s.a>1){s.a=1;s.v*=-1}if(s.a<.08){s.a=.08;s.v*=-1}
   if(!rm){s.x+=s.dx;s.y+=s.dy;if(s.y>H)s.y=0;if(s.x>W)s.x=0;if(s.x<0)s.x=W;}
   x.beginPath();x.arc(s.x,s.y,s.r,0,6.283);
   x.fillStyle='rgba(255,255,255,'+(s.a*.7)+')';x.fill();
   if(s.r>1.2){x.beginPath();x.arc(s.x,s.y,s.r*2.6,0,6.283);x.fillStyle='rgba(232,80,26,'+(s.a*.06)+')';x.fill();}
  }
  if(!rm)requestAnimationFrame(tick);
 }
 tick(); if(rm){/* statico, un solo frame */}
})();
