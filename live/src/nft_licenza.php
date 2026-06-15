<?php
// Genera l SVG dell NFT licenza GENESYS, brandizzato 81+, effetto metallo, stelle, riflesso.
// Parametrico su nodo, regione, grado e numero seriale. Nessuna immagine esterna, tutto vettoriale.
function nftLicenzaSvg($livello='REGIONAL',$regione='—',$sic='SIC-XXXXXXXX',$seriale='00',$grado='FONDATORE'){
  $col = $livello==='NATIONAL' ? ['#f3b016','#fff3c4','#b8860b'] : ['#E8501A','#ffd0b8','#a3380f'];
  $g1=$col[0]; $g2=$col[1]; $g3=$col[2];
  $stelle='';
  for($i=0;$i<46;$i++){
    $x=random_int(8,392); $y=random_int(8,552); $r=random_int(1,100)/100*1.4+0.3; $o=random_int(20,90)/100; $d=random_int(15,50)/10;
    $stelle.="<circle cx='$x' cy='$y' r='$r' fill='#fff' opacity='$o'><animate attributeName='opacity' values='$o;0.1;$o' dur='{$d}s' repeatCount='indefinite'/></circle>";
  }
  $ser=str_pad($seriale,2,'0',STR_PAD_LEFT);
  return <<<SVG
<svg viewBox="0 0 400 560" xmlns="http://www.w3.org/2000/svg" style="width:100%;max-width:330px;border-radius:20px;display:block">
<defs>
 <linearGradient id="metal" x1="0" y1="0" x2="1" y2="1">
   <stop offset="0%" stop-color="$g3"/><stop offset="35%" stop-color="$g1"/>
   <stop offset="50%" stop-color="$g2"/><stop offset="65%" stop-color="$g1"/><stop offset="100%" stop-color="$g3"/>
   <animate attributeName="x1" values="0;1;0" dur="6s" repeatCount="indefinite"/>
   <animate attributeName="x2" values="1;0;1" dur="6s" repeatCount="indefinite"/>
 </linearGradient>
 <radialGradient id="bg" cx="50%" cy="32%" r="80%">
   <stop offset="0%" stop-color="#16161f"/><stop offset="100%" stop-color="#050507"/>
 </radialGradient>
 <linearGradient id="shine" x1="0" y1="0" x2="1" y2="1">
   <stop offset="0%" stop-color="#fff" stop-opacity="0"/><stop offset="48%" stop-color="#fff" stop-opacity="0.5"/><stop offset="52%" stop-color="#fff" stop-opacity="0.5"/><stop offset="100%" stop-color="#fff" stop-opacity="0"/>
 </linearGradient>
 <filter id="glow"><feGaussianBlur stdDeviation="3" result="b"/><feMerge><feMergeNode in="b"/><feMergeNode in="SourceGraphic"/></feMerge></filter>
</defs>
<rect x="3" y="3" width="394" height="554" rx="22" fill="url(#bg)" stroke="url(#metal)" stroke-width="3"/>
<g>$stelle</g>
<rect x="3" y="3" width="394" height="554" rx="22" fill="url(#shine)" opacity="0.0">
  <animate attributeName="opacity" values="0;0.18;0" dur="4.5s" repeatCount="indefinite"/>
</rect>
<text x="200" y="58" text-anchor="middle" font-family="Anton,Arial" font-size="15" letter-spacing="5" fill="$g1">GENESYS 81+</text>
<text x="200" y="80" text-anchor="middle" font-family="JetBrains Mono,monospace" font-size="9" letter-spacing="3" fill="#9aa0b4">LICENZA OPERATIVA · $grado</text>
<g filter="url(#glow)">
 <circle cx="200" cy="190" r="74" fill="none" stroke="url(#metal)" stroke-width="2.5"/>
 <circle cx="200" cy="190" r="60" fill="none" stroke="url(#metal)" stroke-width="1" opacity="0.5">
   <animateTransform attributeName="transform" type="rotate" from="0 200 190" to="360 200 190" dur="22s" repeatCount="indefinite"/>
 </circle>
 <text x="200" y="178" text-anchor="middle" font-family="Anton,Arial" font-size="50" fill="url(#metal)">81+</text>
 <text x="200" y="208" text-anchor="middle" font-family="JetBrains Mono,monospace" font-size="9" letter-spacing="2" fill="$g2">$livello</text>
</g>
<text x="200" y="312" text-anchor="middle" font-family="Anton,Arial" font-size="30" fill="#fff">$regione</text>
<line x1="70" y1="338" x2="330" y2="338" stroke="url(#metal)" stroke-width="1" opacity="0.6"/>
<text x="200" y="372" text-anchor="middle" font-family="JetBrains Mono,monospace" font-size="11" letter-spacing="1" fill="#cfcfe0">$sic</text>
<text x="200" y="398" text-anchor="middle" font-family="JetBrains Mono,monospace" font-size="9" letter-spacing="2" fill="#7c8195">NODO $ser DI 21 · GENESI</text>
<g transform="translate(200,468)">
 <polygon points="0,-34 30,-17 30,17 0,34 -30,17 -30,-17" fill="none" stroke="url(#metal)" stroke-width="2"/>
 <polygon points="0,-22 19,-11 19,11 0,22 -19,11 -19,-11" fill="url(#metal)" opacity="0.18"/>
 <text x="0" y="5" text-anchor="middle" font-family="Anton,Arial" font-size="15" fill="$g1">VIP</text>
</g>
<text x="200" y="532" text-anchor="middle" font-family="JetBrains Mono,monospace" font-size="7.5" letter-spacing="2" fill="#5c6175">LABO TECNIC STUDIO · 81PLUS.ONLINE</text>
</svg>
SVG;
}
