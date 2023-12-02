<?php


require 'Conexion.php';


/*  arreglo de las columnas a mostrar en la tabla */
$columns = ['Nomina', 'Apellido_Paterno', 'Apellido_Materno', 'Nombre', 'Cargo','Area', 'Folio','Estatus', 'Comision'];

/* Nombre de la tabla */
$table = "from_comisiones";

$id = 'Folio';

$campo = isset($_POST['campo']) ? $conn->real_escape_string($_POST['campo']) : null;


/* Filtrado */
$where = '';

if ($campo != null) {
    $where = "WHERE (";

    $cont = count($columns);
    for ($i = 0; $i < $cont; $i++) {
        $where .= $columns[$i] . " LIKE '%" . $campo . "%' OR ";
    }
    $where = substr_replace($where, "", -3);
    $where .= ")";
}

/* Limit */
$limit = isset($_POST['registros']) ? $conn->real_escape_string($_POST['registros']) : 10;
$pagina = isset($_POST['pagina']) ? $conn->real_escape_string($_POST['pagina']) : 0;

if (!$pagina) {
    $inicio = 0;
    $pagina = 1;
} else {
    $inicio = ($pagina - 1) * $limit;
}

$sLimit = "LIMIT $inicio , $limit";

/**
 * Ordenamiento
 */

 $sOrder = "";
 if(isset($_POST['orderCol'])){
    $orderCol = $_POST['orderCol'];
    $oderType = isset($_POST['orderType']) ? $_POST['orderType'] : 'asc';
    
    $sOrder = "ORDER BY ". $columns[intval($orderCol)] . ' ' . $oderType;
 }


/* Consulta */
$sql = "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $columns) . "
FROM $table
$where
$sOrder
$sLimit";
$resultado = $conn->query($sql);
$num_rows = $resultado->num_rows;

/* Consulta para total de registro filtrados */
$sqlFiltro = "SELECT FOUND_ROWS()";
$resFiltro = $conn->query($sqlFiltro);
$row_filtro = $resFiltro->fetch_array();
$totalFiltro = $row_filtro[0];

/* Consulta para total de registro filtrados */
$sqlTotal = "SELECT count($id) FROM $table ";
$resTotal = $conn->query($sqlTotal);
$row_total = $resTotal->fetch_array();
$totalRegistros = $row_total[0];

/* Mostrado resultados */
$output = [];
$output['totalRegistros'] = $totalRegistros;
$output['totalFiltro'] = $totalFiltro;
$output['data'] = '';
$output['paginacion'] = '';

if ($num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        $output['data'] .= '<tr>';
        $output['data'] .= '<td>' . $row['Nomina'] . '</td>';
        $output['data'] .= '<td>' . $row['Apellido_Paterno'] . '</td>';
        $output['data'] .= '<td>' . $row['Apellido_Materno'] . '</td>';
        $output['data'] .= '<td>' . $row['Nombre'] . '</td>';
        $output['data'] .= '<td>' . $row['Cargo'] . '</td>';
        $output['data'] .= '<td>' . $row['Area'] . '</td>';
        $output['data'] .= '<td>' . $row['Folio'] . '</td>';
        $output['data'] .= '<td>' . $row['Estatus'] . '</td>';
        $output['data'] .= '<td><a href="data:application/vnd.openxmlformats-officedocument.wordprocessingml.document;base64,' . base64_encode($row['Comision']) . '" download="Comision.docx">Ver Comision</a></td>';
          
        if ($row['Estatus'] === 'SIN FIRMAR') {
            $output['data'] .= "<td style='width: 300px;'><a class='btn btn-danger delete' href='../conexiones/ModificarComision.php?Folio=" . $row['Folio'] . "'>Modificar</a></td>";
            $output['data'] .= "<td><a class='btn btn-danger delete' href='../conexiones/FirmadaComision.php?Folio=" . $row['Folio'] . "'>Firmar</a></td>";       
         }
        if ($row['Estatus'] === 'FIRMADA') {
            $output['data'] .= "<td><a class='btn btn-danger delete' href='../conexiones/SelladaComision.php?Folio=" . $row['Folio'] . "'>Sellada</a></td>";
        } elseif ($row['Estatus'] == 'COMPLETA') {
        
            $output['data'] .= '<tr>';
        }
   }
} else {
    $output['data'] .= '<tr>';
    $output['data'] .= '<td colspan="7">Sin resultados</td>';
    $output['data'] .= '</tr>';
}

if ($output['totalRegistros'] > 0) {
    $totalPaginas = ceil($output['totalRegistros'] / $limit);

    $output['paginacion'] .= '<nav>';
    $output['paginacion'] .= '<ul class="pagination">';

    $numeroInicio = 1;

    if(($pagina - 4) > 1){
        $numeroInicio = $pagina - 4;
    }

    $numeroFin = $numeroInicio + 9;

    if($numeroFin > $totalPaginas){
        $numeroFin = $totalPaginas;
    }

    for ($i = $numeroInicio; $i <= $numeroFin; $i++) {
        if ($pagina == $i) {
            $output['paginacion'] .= '<li class="page-item active"><a class="page-link" href="#">' . $i . '</a></li>';
        } else {
            $output['paginacion'] .= '<li class="page-item"><a class="page-link" href="#" onclick="nextPage(' . $i . ')">' . $i . '</a></li>';
        }
    }

    $output['paginacion'] .= '</ul>';
    $output['paginacion'] .= '</nav>';
}

echo json_encode($output, JSON_UNESCAPED_UNICODE);


