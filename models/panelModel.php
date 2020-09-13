<?php

Class panelModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getestadistica()
    {
        $sql = "SELECT UPPER(b.NOMBRE) NOMBRE ,COUNT(a.IDDOC) AS CANTIDAD
			FROM `proyecto_documento` a INNER JOIN proyecto_tipo_doc b ON a.IDDOC=b.IDDOC
			GROUP BY a.IDDOC";
        $result = $this->_db->query($sql) or die ('Error en '.$sql);

        return $result;
    }

    public function getpostulantesdiarios()
    {
        $sql = "SELECT FECHAPOSTULACION, COUNT(TOTAL) TOTAL,COUNT(APTO) APTO,  (COUNT(APTO) / COUNT(TOTAL) * 100) APTO_PERC,COUNT(NOAPTO) NOAPTO, (COUNT(NOAPTO) / COUNT(TOTAL) * 100) NOAPTO_PERC,
(COUNT(TOTAL) - COUNT(APTO) - COUNT(NOAPTO)) AS PENDIENTE, ((COUNT(TOTAL) - COUNT(APTO) - COUNT(NOAPTO)) / COUNT(TOTAL) * 100) PENDIENTE_PERC
FROM(
SELECT a.FECHAPOSTULACION, a.IDPERSONA AS  TOTAL , c.`IDPERSONA` AS APTO, d.`IDPERSONA` AS NOAPTO
FROM `sara_persona` a LEFT JOIN `sara_persona_psicologa` c ON a.`IDPERSONA`=c.`IDPERSONA` AND c.`APTO` = 'SI'
LEFT JOIN `sara_persona_psicologa` d ON a.`IDPERSONA`=d.`IDPERSONA` AND d.`APTO` = 'NO'
WHERE a.TIPOPERSONA = 'POS') AS a 
GROUP BY FECHAPOSTULACION
				ORDER BY FECHAPOSTULACION DESC";
        $result = $this->_db->query($sql) or die ('Error en '.$sql);

        return $result;
    }
}

?>