<?php

use Illuminate\Support\Facades\Route;
use App\Cerveceria;
use App\Libro;
use App\Evento;
use App\Editor;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

//Ruta de inicio
Route::get('/', function(){
    return redirect()->route('inicio');
});

Route::get('/inicio', function() {

    $libros = Libro::inRandomOrder()->take(5)->get();
    $libros = $libros->all();

    return view('layouts.content', compact('libros'));
})->name('inicio');



//Rutas para el cliente
Route::get('passwd/{cliente}', 'ClienteController@passwd')->name('cliente.passwd');
Route::get('cliente/compra', 'ClienteController@compra')->name('cliente.compra');
Route::get('cliente/checkout/domicilio', 'ClienteController@checkout_dom')->name('cliente.checkout_dom');
Route::get('cliente/checkout/envio', 'ClienteController@checkout_env')->name('cliente.checkout_env');
Route::get('cliente/checkout/pago', 'ClienteController@checkout_pag')->name('cliente.checkout_pag');
Route::get('cliente/checkout/revision', 'ClienteController@checkout_rev')->name('cliente.checkout_rev');
Route::patch('cliente/{id}/password', 'ClienteController@changePassword')->name('cliente.changePassword');
Route::resource('cliente', 'ClienteController');

//Ruta para domicilio
Route::resource('domicilio', 'DomicilioController');

//Ruta para tarjeta
Route::resource('tarjeta', 'TarjetaController');

//Rutas para editor
//Route::get('/editor/updateList', 'EditorController@updateList')->name('editor.updateList');
//Route::get('/editor/deleteList', 'EditorController@deleteList')->name('editor.deleteList');
//Route::post("/editor/scopeName", "EditorController@scopeName")->name("editor.scopeName");
//Route::post("/editor/scopeDelete", "EditorController@scopeDelete")->name("editor.scopeDelete");
//Route::get('editor/login', 'Auth\EditorLoginController@showLoginForm');
//Route::post('editor/login', 'Auth\EditorLoginController@login')->name('editor.login');
//Route::post('editor/logout', 'Auth\EditorLoginController@logout')->name('editor.logout');
//Route::resource('editor', 'EditorController');

//Rutas para libro
//Route::get('/cerveza/update', 'LibroController@updateList')->name('cerveza.updateList');
//Route::get('/cerveza/delete', 'LibroController@deleteList')->name('cerveza.deleteList');

//Route::post("/cerveza/scopeName", "CervezaController@scopeName")->name("cerveza.scopeName");
//Route::post("/cerveza/scopeDelete", "CervezaController@scopeDelete")->name("cerveza.scopeDelete");

Route::resource('libro', 'LibroController');

//Rutas para pedidos
Route::get('/crearPedido', 'PedidoController@crearPedido')->name('pedido.store');
Route::get('/pedidos', 'PedidoController@showOrders')->name('pedido.showOrders');
Route::get('/cliente/{cliente}/{pedido}', 'PedidoController@showOneOrder')->name('pedido.showOneOrder');

//Ruta para enviar correo
//Route::get('/send-mail', 'PedidoController@sendEmail')->name('pedido.sendEmail');


//Rutas para la tienda
Route::get('tienda/cervezas', 'ShopController@porCerveceria')->name('tienda.porCerveceria');
Route::get('estilo/{estilo}', 'ShopController@porEstilo')->name('tienda.porEstilo');
Route::post('tienda/buscar', 'ShopController@buscar')->name('tienda.buscar');
Route::resource('tienda', 'ShopController');

//Ruta para contacto
Route::get('/contacto', function() {

    return view('layouts_cliente.contacto');
});

//Rutsa para el carrito de compras
Route::resource('cart', 'CartController');
Route::post('cart/coupon', 'CartController@apply')->name('cart.apply');
Route::get('vaciar', function(){
    Cart::clear();
});

//Rutas para Eventos
Route::get('/evento/delete/{id}', 'EventoController@delete')->name('evento.delete');
Route::get('/evento/delete', 'EventoController@deleteList')->name('evento.deleteList');
Route::post("/evento/scopeName", "EventoController@scopeName")->name("evento.scopeName");
Route::post("/evento/scopeDelete", "EventoController@scopeDelete")->name("evento.scopeDelete");
Route::resource('evento', 'EventoController');

//Rutas para Reportes
Route::post('/reporte/nuevo', 'ReporteController@createReport')->name('reporte.nuevo');
Route::get('/reporte/create/pdf', 'ReporteController@createPdf')->name('reporte.pdf');
Route::get('/reporte/view', 'ReporteController@view')->name('reporte.view');
Route::get('/reporte/select/{reporte}', 'ReporteController@select')->name('reporte.select');
Route::get('/reportes', 'ReporteController@viewReports')->name('reporte.list');
Route::resource('reporte', 'ReporteController');
