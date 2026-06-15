<?php
// core81/document_builder.php — DOC81+ generatore documenti
declare(strict_types=1);
require_once __DIR__ . '/db.php';

class DocumentBuilder {
    private const TIPI_SUPPORTATI = [
        'DVR','DUVRI','POS','PSC','PIMUS','MMC',
        'RISCHIO_CHIMICO','RISCHIO_BIOLOGICO','RUMORE','VIBRAZIONI',
        'STRESS_LAVORO','PROCEDURE_SICUREZZA','NOMINE','VERBALI','CHECKLIST',
        'MANUALE_HACCP','REGISTRO_TEMPERATURE','REGISTRO_PULIZIE',
        'REGISTRO_SANIFICAZIONE','REGISTRO_NON_CONFORMITA',
        'REGISTRO_MANUTENZIONI_HACCP','LEGIONELLA',
        'PRIVACY_POLICY','REGISTRO_TRATTAMENTI','NOMINE_PRIVACY',
        'INFORMATIVA_PRIVACY','LETTERA_INCARICO','VALUTAZIONE_PRIVACY','DATA_BREACH',
    ];

    public static function create(int $uid, string $tipo, string $titolo, array $dati): array {
        if (!in_array(strtoupper($tipo), self::TIPI_SUPPORTATI, true)) {
            throw new \InvalidArgumentException("Tipo documento non supportato: $tipo");
        }

        $db = DB::get();
        $db->prepare('INSERT INTO doc81_documents (user_id, tipo, titolo, dati_input, status)
                      VALUES (?,?,?,?,"GENERATO")')
           ->execute([$uid, strtoupper($tipo), $titolo, json_encode($dati)]);
        $doc_id = (int)$db->lastInsertId();

        // TODO: generazione PDF reale con TCPDF/DomPDF
        $pdf_url = "/api/create-document.php?doc_id=$doc_id&download=1";

        $db->prepare('UPDATE doc81_documents SET pdf_path = ? WHERE id = ?')
           ->execute([$pdf_url, $doc_id]);

        return ['id' => $doc_id, 'pdf_url' => $pdf_url];
    }
}
