/** 81+ MOTHERBOARD · file 50 · GAMIFICATION81+ (status/badge/leaderboard, tetti per status). */
var GAM_CAPS = { MEMBER:[3,12,40], NETWORKER:[5,25,90], ELITE:[8,40,150], FRANCHISER:[12,60,220], CLUB:[20,100,360] };
function GAM_capFor(status){ return GAM_CAPS[String(status||'MEMBER').toUpperCase()] || GAM_CAPS.MEMBER; }
function GAM_leaderboardPull(){
  var j=MB_json_(MB.BASE.SFERA+'rewards/log.php');
  MB_log_('GAMIFICATION81','INFO','leaderboard pull '+(j&&j.ok));
  return j;
}
