<?php

namespace App\Http\Controllers;

use DateTime;
use App\Libro;
use App\Pedido;
use App\Editor;
use App\Reporte;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class ReporteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $editor_id = \Auth::guard('editor')->user()->id;
      $admin = Editor::where('id', $editor_id)->first();
      return view('layouts_editor.editorCrearReporte', compact('admin'));
    }

    public function view(Request $request)
    {
      $editor_id = \Auth::guard('editor')->user()->id;
      $admin = Editor::where('id', $editor_id)->first();

      $reporte = DB::table('reportes')->latest('created_at')->first();
      $reporte_id = $reporte->id;
      $reporte = Reporte::where('id', $reporte->id)->first();
      $id = $reporte->id;

      //Consulta API
      $req = Request::create('api/reporte/'.$id, 'GET');
      $res = \Route::dispatch($req);
      $datos = json_decode($res->getContent(), true);

      return view('layouts_editor.editorReporte', compact('admin', 'datos', 'reporte'));
    }

    public function select(Request $request, Reporte $reporte)
    {

      $editor_id = \Auth::guard('editor')->user()->id;
      $admin = Editor::where('id', $editor_id)->first();

      $id = $reporte->id;

      //Consulta API
      $req = Request::create('api/reporte/'.$id, 'GET');
      $res = \Route::dispatch($req);
      $datos = json_decode($res->getContent(), true);

      return view('layouts_editor.editorReporte', compact('admin', 'datos', 'reporte'));
    }

    public function createReport(Request $request)
    {

      $request->validate([
        'editor_id' => 'required|numeric',
        'periodo' => 'required|string',
        'fecha_inicio' => 'required|date',
      ]);

      $today = new DateTime();
      $init_date = new DateTime($request->fecha_inicio);

      if($today < $init_date)
      {
        return redirect()->back()->with('error_message', 'La fecha ingresada es mayor que la actual, favor de corregir');
      }

      $primer_reporte = Reporte::find(1);
      $fecha_primer_reporte = new DateTime($primer_reporte->fecha_inicio);

      if($init_date < $fecha_primer_reporte)
      {
        return redirect()->back()->with('error_message', 'La fecha ingresada no corresponde a la fecha de inicio de operaciones de la tienda');
      }
      
      //Se crea un string para pasarlo a la función date_interval_create_from_date_string()
      $periodo = "";
      if($request->periodo == "semanal")
      {
        $periodo = '7 days';
      }
      elseif($request->periodo == "quincenal")
      {
        $periodo = '15 days';
      }
      elseif($request->periodo == "mensual")
      {
        $periodo = '1 month';
      }

      //Se obtiene la fecha final de acuerdo a la fecha_inicio y el periodo seleccionado
      $fecha_inicio = $request->fecha_inicio;
      $fecha_final = date_create($fecha_inicio);
      date_add($fecha_final, date_interval_create_from_date_string($periodo));
      $fecha_final = date_format($fecha_final, 'Y-m-d');
      $fecha_final = new DateTime($fecha_final);

      if($today < $fecha_final)
      {
        return redirect()->back()->with('error_message', 'Verifica el periodo o la fecha de inicio seleccionada');
      }

      //Si no hay ningún error, se procede a guardar el reporte.
      $reporte = new Reporte();

      $reporte->editor_id = $request->editor_id;
      $reporte->periodo = $request->periodo;
      $reporte->fecha_inicio = $request->fecha_inicio;
      $reporte->save();

      return redirect()->route('reporte.view');
    }

    public function viewReports()
    {
      $editor_id = \Auth::guard('editor')->user()->id;
      $admin = Editor::where('id', $editor_id)->first();

      $reportes = Reporte::all();

      return view('layouts_editor.editorListaReportes', compact('admin', 'reportes'));
    }

    public function createPdf(Request $request)
    {
      $id = $request->reporte_id;

      $reporte = Reporte::where('id', $id)->first();

      //Consulta API
      $req = Request::create('api/reporte/'.$id, 'GET');
      $res = \Route::dispatch($req);
      $datos = json_decode($res->getContent(), true);

      $pdf = PDF::loadView('partials.pdf-report', compact('datos', 'reporte'));

      return $pdf->stream('archivo.pdf');
    }

    public function getInfoReport($reporte)
    {
      //Se obtiene el reporte deseado
      $report = Reporte::where('id', $reporte)->first();

      //Se crea un string para pasarlo a la función date_interval_create_from_date_string()
      $periodo = "";
      if($report->periodo == "semanal")
      {
        $periodo = '7 days';
      }
      elseif($report->periodo == "quincenal")
      {
        $periodo = '15 days';
      }
      elseif($report->periodo == "mensual")
      {
        $periodo = '1 month';
      }

      //Se obtiene la fecha final de acuerdo a la fecha_inicio y el periodo seleccionado
      $fecha_inicio = $report->fecha_inicio;
      $fecha_final = date_create($fecha_inicio);
      date_add($fecha_final, date_interval_create_from_date_string($periodo));
      $fecha_final = date_format($fecha_final, 'Y-m-d');

      //Se procede a obtener los pedidos en el intervalo establecido
      $pedidos = Pedido::whereBetween('fecha', [$fecha_inicio, $fecha_final])->get()->all();

      //Se declaran los arrays donde se van a guardar los datos de interés para mostrar en el reporte
      $array_id = array(); // array de id's
      $array_nombre = array(); //array de nombres de cervezas
      $array_cantidad = array(); //array de cantidad por cerveza vendida
      $array_total_por_cerv = array(); //array del monto total por cerveza vendida

      $cant_total = 0;
      $suma_total = 0;
      $envios_normales = 0;
      $envios_expres = 0;
      $pagos_tarjeta = 0;
      $pagos_paypal = 0;
      $total_pedidos = 0;

      foreach($pedidos as $pedido)
      {
        foreach($pedido->libros as $libro)
        {
          $total_por_cerv = $libro->pivot->cantidad * $libro->precio;

          if(empty($array_id))
          {
            $array_id[]= $libro->id;
            $array_nombre[] = $libro->nombre;
            $array_cantidad[] = $libro->pivot->cantidad;
            $array_total_por_cerv[] = $total_por_cerv;
          }
          else //El array ya tiene al menos un elemento
          {
            //Se van a agregar los elementos si el id todavía no existe
            if(!in_array($libro->id, $array_id))
            {
              $array_id[]= $libro->id;
              $array_nombre[] = $libro->nombre;
              $array_cantidad[] = $libro->pivot->cantidad;
              $array_total_por_cerv[] = $total_por_cerv;
            }
            else{ 
              //Si existe el id, se actualiza la cantidad del producto en su respectivo array,
              //obteniendo el índice del array_id y pasándolo como parámetro a los arrays de cantidad y monto.
              $array_cantidad[array_search($libro->id, $array_id, true)] += $libro->pivot->cantidad;
              $array_total_por_cerv[array_search($libro->id, $array_id, true)] += $total_por_cerv;
            }
          }
        }

        //Se obtienen la cantidad de cervezas vendidas, el monto por las cervezas vendidas,
        //cantidad de envíos normales y exprés, y de pagos con tarjeta o paypal.
        $cant_total += $pedido->cantidad;
        $suma_total += $pedido->total;
        if($pedido->metodo_envio == 'normal')
        {
          $envios_normales++;
        }
        elseif($pedido->metodo_envio == 'expres')
        {
          $envios_expres++;
        }

        if($pedido->metodo_pago == 'tarjeta')
        {
          $pagos_tarjeta++;
        }
        elseif($pedido->metodo_pago == 'paypal')
        {
          $pagos_paypal++;
        }
      }

      //Se ordenan todos los array simultáneamente en orden ascendente
      array_multisort($array_cantidad, $array_id, $array_nombre, $array_total_por_cerv);

      //Se crea un arreglo final para devolver con todos los datos obtenidos
      $array_final =array(
        'libros_id' => array_reverse($array_id),
        'libros_nombre' => array_reverse($array_nombre),
        'libros_cantidad' => array_reverse($array_cantidad),
        'libros_monto' => array_reverse($array_total_por_cerv),
        'total_cervezas_vendidas' => $cant_total,
        'monto_vendido' => $suma_total,
        'envios_normales' => $envios_normales,
        'envios_expres' => $envios_expres,
        'pagos_tarjeta' => $pagos_tarjeta,
        'pagos_paypal' => $pagos_paypal,
        'total_libros' => count($array_id),
        'fecha_inicio' => $fecha_inicio,
        'fecha_final' => $fecha_final
      );
      return $array_final;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Reporte  $reporte
     * @return \Illuminate\Http\Response
     */
    public function show(Reporte $reporte)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Reporte  $reporte
     * @return \Illuminate\Http\Response
     */
    public function edit(Reporte $reporte)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Reporte  $reporte
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reporte $reporte)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Reporte  $reporte
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reporte $reporte)
    {
        //
    }
}
