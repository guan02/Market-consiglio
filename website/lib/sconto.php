<?php
enum TipoSconto {
    case Quantita;
    case PrezzoTotale;
    case Stagione;

    public function from(string $s) : TipoSconto {
        
        switch ($s) {
            case "quantita":
                return TipoSconto::Quantita;
            case "prezzoTotale":
                return TipoSconto::PrezzoTotale;
            case "stagione":
                return TipoSconto::Stagione;
            default:
                throw new Exception("Tipo sconto non valido");
        }
    }
}
