<?php
//Seguimiento CEX SOAP
if (!defined('USUARIO_CEX')) {
    //DATOS DE CONFIGURACION DE LA CUENTA
    //Usuario y contraseña, los aporta correos express para sus modulos de ecommerce.
    define('USUARIO_CEX', '!!!Poner usuario!!!!');
    define('CONTRASENA_CEX', '!!!Poner contraseña!!!');
    //Codigo de facturacion, aportado por correos express (En el caso de que tengas varios te lo curras vago.)
    define('CODIGO_CLIENTE', '!!!Poner codigo de facturacion');
    //Url de acceso al WebService de CEX 
    define('URL_SEGUIMIENTO', 'https://www.correosexpress.com/wpsc/services/SeguimientoEnvio?wsdl');
}

class cex
{
    function __construct($numero_de_envio)
    {
        $cuerpo_soap   = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:mes="messages.seguimientoEnvio.ws.chx.es">
            <soapenv:Header/>
            <soapenv:Body>
            <mes:seguimientoEnvio>
            <mes:solicitante>P' . CODIGO_CLIENTE . '</mes:solicitante>
            <mes:dato>' . $numero_de_envio . '</mes:dato>
            <!--Optional:-->
            <mes:password></mes:password>
            </mes:seguimientoEnvio>
            </soapenv:Body>
            </soapenv:Envelope>';

        $llamada = array(
            'soap' => $cuerpo_soap,
            'url'  => URL_SEGUIMIENTO
        );
        $xml_molon = self::procesarCurl($llamada);
        $lolaflores = SimpleXML_Load_String(trim($xml_molon));
        $lolaflores = $lolaflores->getNamespaces(TRUE);
        $llaves = array_keys($lolaflores);
        foreach ($llaves as $key) {
            $buscar
                = '#'
                . '('
                . '\<'
                . '/?'
                . preg_quote($key)
                . ')'
                . '('
                . ':{1}'
                . ')'
                . '#';
            $y_cambiar_por
                = '$1'
                . '_';
            // PERFORM THE REPLACEMENT
            $xml_molon =  preg_replace($buscar, $y_cambiar_por, $xml_molon);
        }
        $resultado = json_decode(json_encode(SimpleXML_Load_String($xml_molon, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        $fecha = (string) $resultado['soapenv_Body']['ns3_seguimientoEnvioResponse']['ns3_return']['ns1_fechaEstado'];
        $fecha = $fecha[0] . $fecha[1] . "-" . $fecha[2] . $fecha[3] . "-" . $fecha[4] . $fecha[5] . $fecha[6] . $fecha[7];
        $hora = (string) $resultado['soapenv_Body']['ns3_seguimientoEnvioResponse']['ns3_return']['ns1_fechaEstado'];
        $hora = $hora[0] . $hora[1] . ":" . $hora[2] . $hora[3];
        $cp = (string) $resultado['soapenv_Body']['ns3_seguimientoEnvioResponse']['ns3_return']['ns1_codPosNacDest'];
        $cpBis = $cp[0] . $cp[1];
        switch ($cpBis) {
            case '01':
                $provincia = "Álava";
                break;
            case '02':
                $provincia = "Albacete";
                break;
            case '03':
                $provincia = "Alicante";
                break;
            case '04':
                $provincia = "Almería";
                break;
            case '05':
                $provincia = "Ávila";
                break;
            case '06':
                $provincia = "Badajoz";
                break;
            case '07':
                $provincia = "Baleares ";
                break;
            case '08':
                $provincia = "Barcelona";
                break;
            case '09':
                $provincia = "Burgos";
                break;
            case '10':
                $provincia = "Cáceres";
                break;
            case '11':
                $provincia = "Cádiz";
                break;
            case '12':
                $provincia = "Castellón";
                break;
            case '13':
                $provincia = "Ciudad Real";
                break;
            case '14':
                $provincia = "Córdoba";
                break;
            case '15':
                $provincia = "La Coruña";
                break;
            case '16':
                $provincia = "Cuenca";
                break;
            case '17':
                $provincia = "Gerona ";
                break;
            case '18':
                $provincia = "Granada";
                break;
            case '19':
                $provincia = "Guadalajara";
                break;
            case '20':
                $provincia = "Guipúzcoa";
                break;
            case '21':
                $provincia = "Huelva";
                break;
            case '22':
                $provincia = "Huesca";
                break;
            case '23':
                $provincia = "Jaén";
                break;
            case '24':
                $provincia = "León";
                break;
            case '25':
                $provincia = "Lérida";
                break;
            case '26':
                $provincia = "La Rioja";
                break;
            case '27':
                $provincia = "Lugo";
                break;
            case '28':
                $provincia = "Madrid";
                break;
            case '29':
                $provincia = "Málaga";
                break;
            case '30':
                $provincia = "Murcia";
                break;
            case '31':
                $provincia = "Navarra";
                break;
            case '32':
                $provincia = "Orense";
                break;
            case '33':
                $provincia = "Asturias";
                break;
            case '34':
                $provincia = "Palencia";
                break;
            case '35':
                $provincia = "Las Palmas";
                break;
            case '36':
                $provincia = "Pontevedra";
                break;
            case '37':
                $provincia = "Salamanca";
                break;
            case '38':
                $provincia = "Santa Cruz de Tenerife";
                break;
            case '39':
                $provincia = "Cantabria";
                break;
            case '40':
                $provincia = "Segovia";
                break;
            case '41':
                $provincia = "Sevilla";
                break;
            case '42':
                $provincia = "Soria";
                break;
            case '43':
                $provincia = "Tarragona";
                break;
            case '44':
                $provincia = "Teruel";
                break;
            case '45':
                $provincia = "Toledo";
                break;
            case '46':
                $provincia = "Valencia";
                break;
            case '47':
                $provincia = "Valladolid";
                break;
            case '48':
                $provincia = "Vizcaya";
                break;
            case '49':
                $provincia = "Zamora";
                break;
            case '50':
                $provincia = "Zaragoza";
                break;
            case '51':
                $provincia = "Ceuta";
                break;
            case '52':
                $provincia = "Melilla";
                break;
            default:
                $provincia = "Desconocida";
        }
        $respuesta = array(
            'destinatario' => ucwords(strtolower($resultado['soapenv_Body']['ns3_seguimientoEnvioResponse']['ns3_return']['ns1_contacDest'])),
            'estado_actual' => ucfirst(strtolower($resultado['soapenv_Body']['ns3_seguimientoEnvioResponse']['ns3_return']['ns1_descEstado'])),
            'fecha_estado_actual' => $fecha,
            'hora_estado_actual' => $hora,
            'calle_destinatario' => ucwords(strtolower(str_replace("/", "/ ", $resultado['soapenv_Body']['ns3_seguimientoEnvioResponse']['ns3_return']['ns1_dirDest']))),
            'poblacion_destinatario' => ucwords(strtolower($resultado['soapenv_Body']['ns3_seguimientoEnvioResponse']['ns3_return']['ns1_pobDest'])),
            'cp_destinatario' => $cp,
            'provincia_destinatario' => $provincia,
            'correo_destinatario' => $resultado['soapenv_Body']['ns3_seguimientoEnvioResponse']['ns3_return']['ns1_emailDest'],
            'telefono_destinatario' => $resultado['soapenv_Body']['ns3_seguimientoEnvioResponse']['ns3_return']['ns1_telefDest'],
            'historial' => array(),
        );
        foreach ($resultado['soapenv_Body']['ns3_seguimientoEnvioResponse']['ns3_return']['ns1_historicoEstados'] as $i) {
            $fecha = (string) $i['ns1_fechaEstado'];
            $fecha = $fecha[0] . $fecha[1] . "-" . $fecha[2] . $fecha[3] . "-" . $fecha[4] . $fecha[5] . $fecha[6] . $fecha[7];
            $hora = (string) $i['ns1_horaEstado'];
            $hora = $hora[0] . $hora[1] . ":" . $hora[2] . $hora[3];
            $temp = array(
                'estado' => ucfirst(strtolower($i['ns1_descEstado'])),
                'fecha' => $fecha,
                'hora' => $hora,
            );
            array_push($respuesta['historial'], $temp);
        }
        $this->envio = $respuesta;
    }    
    /**
     * getNombreDestinatario
     *
     * Retorna el nombre del destinatario
     * @return string
     */
    function getNombreDestinatario()
    {
        return $this->envio['destinatario'];
    }    
    /**
     * getEstadoActual
     *
     * Retorna el estado actual del destinatario
     * @return string
     */
    function getEstadoActual()
    {
        return $this->envio['estado_actual'];
    }    
    /**
     * getDateEstadoActual
     *
     * Retorna un objeto DateTime con la fecha y hora del estado actual.
     * @return object
     */
    function getDateEstadoActual()
    {
        $date =  $this->envio['fecha_estado_actual'] . " " . $this->envio['hora_estado_actual'];
        return new DateTime($date);
    }    
    /**
     * getDireccionDestinatario
     *
     * Retorna la primera linea de la direccion del destinatario
     * @return int
     */
    function getDireccionDestinatario()
    {
        return $this->envio['calle_destinatario'];
    }    
    /**
     * getCodigoPostalDestinatario
     *
     * Retorna el codigo postal del destinatario
     * OJO -> Devuelve string para evitar que vuele el primer 0
     * @return string
     */
    function getCodigoPostalDestinatario()
    {
        return $this->envio['cp_destinatario'];
    }    
    /**
     * getPoblacion_Destinatario
     *
     * Retorna la poblacion del destinatario
     * @return string
     */
    function getPoblacion_Destinatario()
    {
        return $this->envio['poblacion_destinatario'];
    }    
    /**
     * getProvincia_Destinatario
     *
     * Retorna la provincia del destinatario
     * @return string
     */
    function getProvincia_Destinatario()
    {
        return $this->envio['provincia_destinatario'];
    }    
    /**
     * getCorreoDestinatario
     *
     * Retorna el email del destinatario
     * @return string
     */
    function getCorreoDestinatario()
    {
        return $this->envio['correo_destinatario'];
    }    
    /**
     * getTelefonoDestinatario
     *
     * Retorna el telefono del destinatario
     * OJO -> No contiene el codigo internacional
     * @return int
     */
    function getTelefonoDestinatario()
    {
        return $this->envio['telefono_destinatario'];
    }    
    /**
     * getHistorial
     *
     * Retorna un array multidimensional con los cambios de estado
     * [0]['estado'] -> string estado
     *    ['fecha'] -> string fecha (d-m-y)
     *    ['hora'] -> string hora (H:i)
     * [1] etc...
     * 
     * @return array
     */
    function getHistorial()
    {
        return $this->envio['historial'];
    }
    
    /**
     * procesarCurl
     *
     * @param  array
     * array(
     *   'soap' => $soap,
     *   'url'  => URL
     * ); 
     * @return string
     */
    private function procesarCurl($llamada)
    {
        $login = array(
            'usuario'  => USUARIO_CEX,
            'password' => CONTRASENA_CEX
        );
        $cabecera = array(
            "Content-type: text/xml;charset=\"utf-8\"",
            "Accept: text/xml",
            "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.3",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "SOAPAction: \"" . $llamada['url'] . "\""
        );

        $ch         = curl_init();
        $opciones    = array(
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_SSL_VERIFYHOST  => false,
            CURLOPT_SSL_VERIFYPEER  => false,
            CURLOPT_USERAGENT       => 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:21.0) Gecko/20100101 Firefox/21.0)',
            CURLOPT_URL             => $llamada['url'],
            CURLOPT_USERPWD         => $login['usuario'] . ":" . $login['password'],
            CURLOPT_POST            => true,
            CURLOPT_POSTFIELDS      => mb_convert_encoding($llamada['soap'], mb_detect_encoding($llamada['soap']), "UTF-8"),
            CURLOPT_HTTPHEADER      => $cabecera,
        );
        curl_setopt_array($ch, $opciones);
        $respuesta = curl_exec($ch);
        curl_close($ch);
        return $respuesta;
    }
}
