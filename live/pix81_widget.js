// 81+ WIDGET PIX81+ STATUS. Inserisci <div id="pix81-widget"></div> dove vuoi mostrarlo.
// Mostra disponibili, posseduti, elite space e una CTA verso https://81plus.place.
(function(){
  function css(){
    if(document.getElementById('pix81w-css'))return;
    const s=document.createElement('style'); s.id='pix81w-css';
    s.textContent=`
    .pix81w{background:linear-gradient(135deg,rgba(232,80,26,.1),rgba(138,74,232,.06));border:1px solid #E8501A;border-radius:16px;padding:18px;font-family:'Sora',Arial,sans-serif;color:#c8c8d2;position:relative;overflow:hidden}
    .pix81w::before{content:'';position:absolute;top:-30px;right:-30px;width:90px;height:90px;background:radial-gradient(circle,rgba(251,107,0,.3),transparent 70%)}
    .pix81w .kick{font-family:'Anton','Arial';font-size:12px;letter-spacing:2px;color:#FB6B00;text-transform:uppercase}
    .pix81w h4{font-family:'Anton','Arial';color:#fff;font-size:20px;margin:2px 0 10px}
    .pix81w .grid{display:flex;gap:14px;margin-bottom:12px;flex-wrap:wrap}
    .pix81w .k{flex:1;min-width:70px}
    .pix81w .k .v{font-family:'Anton','Arial';font-size:24px;color:#E8501A}
    .pix81w .k .v.green{color:#3FBF6B}
    .pix81w .k .l{font-size:10px;color:#8a8a96;text-transform:uppercase;letter-spacing:1px}
    .pix81w .es{display:inline-block;font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px;margin-bottom:12px}
    .pix81w .es.on{background:rgba(63,191,107,.16);color:#3FBF6B}
    .pix81w .es.off{background:rgba(138,138,150,.16);color:#8a8a96}
    .pix81w a.cta{display:block;text-align:center;background:linear-gradient(135deg,#E8501A,#FB6B00);color:#fff;font-weight:800;font-family:'Anton','Arial';letter-spacing:.5px;padding:11px;border-radius:10px;text-decoration:none;position:relative;z-index:2}
    .pix81w .bar{height:5px;background:#26262f;border-radius:3px;overflow:hidden;margin:4px 0 12px}
    .pix81w .bar span{display:block;height:100%;background:linear-gradient(90deg,#E8501A,#FB6B00)}`;
    document.head.appendChild(s);
  }
  async function render(el){
    let st={loggato:false,disponibili:'-',totali:1000,posseduti:0,elite_space:false,titolo:''};
    try{ const r=await fetch('api/pixel.php?az=stato'); const d=await r.json(); if(d.ok) st=Object.assign(st,d); }catch(e){
      try{ const rf=await fetch('api/pixel.php?az=fomo'); const df=await rf.json(); if(df.ok){ st.disponibili=df.disponibili; st.totali=df.totali; } }catch(e2){}
    }
    const occ=st.totali-st.disponibili;
    el.className='pix81w';
    el.innerHTML=`
      <div class="kick">Metaverso 81+</div>
      <h4>PIX81+ Founding Node</h4>
      <div class="grid">
        <div class="k"><div class="v green">${(+st.disponibili).toLocaleString('it-IT')}</div><div class="l">Disponibili</div></div>
        <div class="k"><div class="v">${st.totali}</div><div class="l">Totali</div></div>
        <div class="k"><div class="v">${st.posseduti||0}</div><div class="l">I tuoi</div></div>
      </div>
      <div class="bar"><span style="width:${occ/st.totali*100}%"></span></div>
      ${st.loggato?`<div class="es ${st.elite_space?'on':'off'}">${st.elite_space?'⭐ Elite Space ATTIVO · '+st.titolo:'Elite Space non attivo'}</div>`:''}
      <a class="cta" href="https://81plus.place">${st.elite_space?'Aggiungi un PIX':'Sblocca il tuo Elite Space'}</a>`;
  }
  function init(){ css(); document.querySelectorAll('#pix81-widget,.pix81-widget').forEach(render); }
  if(document.readyState==='loading') document.addEventListener('DOMContentLoaded',init); else init();
})();
