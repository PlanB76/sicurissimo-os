<?php
/* 81+ AUDIT LEAD CAPTURE
   Salva nome, email, settore, dipendenti dal form audit.
   Registra il lead come freddo nel CRM e lo accoda alla welcome flow.
   public_html/api/audit_lead.php */

require_once __DIR__.'/db.php';
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
  echo json_encode(['ok'=>false,'errore'=>'solo POST']); exit;
}

$nome = substr(trim($_POST['nome'] ?? ''), 0, 100);
$email = substr(trim($_POST['email'] ?? ''), 0, 120);
$settore = substr(trim($_POST['settore'] ?? ''), 0, 60);
$dipendenti = substr(trim($_POST['dipendenti'] ?? ''), 0, 20);

if(!$nome || !$email){
  echo json_encode(['ok'=>false,'errore'=>'nome e email obbligatori']); exit;
}
if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
  echo json_encode(['ok'=>false,'errore'=>'email non valida']); exit;
}

try { $pdo = db(); } catch(Throwable $e){
  echo json_encode(['ok'=>false,'errore'=>'db non disponibile']); exit;
}

/* salva nel CRM leads se la tabella esiste */
try {
  $chk = $pdo->query("SHOW TABLES LIKE 'crm_leads'")->fetch();
  if($chk){
    $ins = $pdo->prepare("INSERT INTO crm_leads (nome,email,canale,stato,interesse,ultimo_messaggio,azione_successiva,creato_il,aggiornato_il) VALUES (?,?,'web','freddo',?,?,?,NOW(),NOW()) ON DUPLICATE KEY UPDATE aggiornato_il=NOW(), interesse=VALUES(interesse)");
    $ins->execute([$nome, $email, 'Settore: '.$settore.', '.$dipendenti.' dip.', 'Audit compilato', 'Inviare risultato audit e follow-up']);
  }
} catch(Throwable $e){}

/* salva nella tabella lead universale se esiste */
try {
  $chk2 = $pdo->query("SHOW TABLES LIKE 'lead'")->fetch();
  if($chk2){
    $hash = md5(strtolower(trim($email)));
    $pdo->prepare("INSERT IGNORE INTO lead (nome,email,fonte,settore,dimensione,gdpr_consenso,gdpr_data,hash_email) VALUES (?,?,'audit',?,?,1,NOW(),?)")
      ->execute([$nome, $email, $settore, $dipendenti, $hash]);
  }
} catch(Throwable $e){}

/* accoda alla welcome flow (Brevo API se configurata) */
$brevo_key = getenv('BREVO_API_KEY');
if($brevo_key){
  $ch = curl_init('https://api.brevo.com/v3/contacts');
  curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => ['api-key: '.$brevo_key, 'Content-Type: application/json'],
    CURLOPT_POSTFIELDS => json_encode([
      'email' => $email,
      'attributes' => ['FIRSTNAME' => $nome, 'SETTORE' => $settore, 'DIPENDENTI' => $dipendenti, 'FONTE' => 'audit'],
      'listIds' => [2], /* lista 81plus_iscritti */
      'updateEnabled' => true
    ]),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 5
  ]);
  curl_exec($ch);
  curl_close($ch);
}

echo json_encode(['ok'=>true,'nota'=>'Lead salvato e accodato al welcome flow.']);
