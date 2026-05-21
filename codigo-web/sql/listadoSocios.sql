  DELIMITER $$
  DROP PROCEDURE IF EXISTS sp_listado_socios $$

  CREATE PROCEDURE sp_listado_socios (IN elidempresa TEXT)

  BEGIN

    DECLARE ssql TEXT;

    @ssql = "SELECT cod_prof, ced_prof, CONCAT( ape_prof, ' ', nombr_prof ) AS nombrecompleto, UPPER( statu_prof ) AS  STATUS ,  (hab_f_prof + hab_f_empr + hab_f_extr + hab_f_capi) AS ahorros FROM sgcaf200 ORDER BY cod_prof";


-- ,
     -- concat(SUBSTR(ced_prof,1,4),'.',SUBSTR(ced_prof,5,3),'.',SUBSTR(ced_prof,8,3)) AS cf
-- ALTER TABLE `suspende` ADD `codigo` VARCHAR( 5 ) NOT NULL AFTER `monto` ;
-- update  suspende 
--   set codigo = (
--     select cod_prof 
--       from sgcaf200 
--       where ced_prof=concat(substr(cedula,1,4),substr(cedula,6,3),substr(cedula,10,3))
--       ) 
  -- select concat(substr(cedula,1,4),substr(cedula,6,3),substr(cedula,10,3)) from suspende


-- SELECT cod_prof, ced_prof, CONCAT( ape_prof, ' ', nombr_prof ) AS nombrecompleto, UPPER( statu_prof ) AS
-- STATUS , (
-- hab_f_prof + hab_f_empr + hab_f_extr + hab_f_capi
-- ) AS ahorros, CONCAT( SUBSTR( ced_prof, 1, 4 ) , '.', SUBSTR( ced_prof, 5, 3 ) , '.', SUBSTR( ced_prof, 8, 3 ) ) AS cf, (

-- SELECT SUM( monto )
-- FROM `suspende`
-- WHERE (
-- activo =1
-- AND suspende.codigo = cod_prof
-- )
-- GROUP BY codigo
-- ) AS mono
-- FROM sgcaf200
-- ORDER BY cod_prof

-- SELECT (sum(monto) FROM `suspende` WHERE activo=1 and suspende.cedula = cf) as monopendiente  

    PREPARE stmt FROM @ssql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
  END $$

  DELIMITER ;

  -- call sp_blc_comprobacion("CAT-CR",6,0,'1','S');
--  