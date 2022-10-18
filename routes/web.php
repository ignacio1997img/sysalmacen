<?php

use App\Http\Controllers\ArticleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\EgressController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\IncomeSolicitudController;
use App\Http\Controllers\BandejaController;

use App\Http\Controllers\IncomeDonorController;
use App\Http\Controllers\EgressDonorController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\DonacionSolicitudController;
use App\Http\Controllers\SucursalController;
use App\Http\Controllers\ProviderController;


use App\Http\Controllers\DonationStockController;
use App\Http\Controllers\ReportAlmacenController;
use App\Models\Article;
use App\Models\Provider;
use App\Models\Sucursal;

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

Route::get('login', function () {
    return redirect('admin/login');
})->name('login');

Route::get('/', function () {
    return redirect('admin');
});


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();

    Route::resource('usuario', UserController::class);
    Route::post('usuarios/desactivar', [UserController::class, 'desactivar'])->name('almacen_desactivar');
    Route::post('usuarios/activar', [UserController::class, 'activar'])->name('almacen_activar');

    
    //........................  INCOME
    Route::resource('income', IncomeController::class);
    Route::get('incomes/browse/view/{id?}', [IncomeController::class, 'view_ingreso'])->name('income_view');
    Route::get('incomes/browse/view/stock/{id?}', [IncomeController::class, 'view_ingreso_stock'])->name('income_view_stock');
    Route::delete('incomes/browse/delete', [IncomeController::class, 'destroy'])->name('income_delete');
    Route::post('incomes/update', [IncomeController::class, 'update'])->name('income_update');
    Route::get('incomes/browse/{income?}/salida', [IncomeController::class, 'salida'])->name('incomes-browse.salida');

    // Route::get('incomes/browse/edit/{id?}', [IncomeController::class, 'edit'])->name('edit_income');


    // Route::get('incomes/selectproveedor', [IncomeController::class, 'select_proveedor'])->name('select_proveedor');
    // Route::get('incomes/selectproveedorsearch', [IncomeController::class, 'ajax_proveedor']);

   

   //........................  EGRES
    Route::resource('egres', EgressController::class);
    Route::post('egres/update', [EgressController::class, 'update'])->name('egres_update');
    Route::post('egres/delete', [EgressController::class, 'destroy'])->name('egres_delete');
    // Route::get('egres/view/{id?}', [EgressController::class, 'view_egreso'])->name('egreso_view_entregado');// en mantenimiento sin funcionamiento 

    Route::get('sucursals', [SucursalController::class, 'index'])->name('voyager.sucursals.index');
    Route::get('sucursals/{sucursal?}/da/index', [SucursalController::class, 'indexDireccion'])->name('sucursal-da.index');
    Route::post('sucursals/da/store', [SucursalController::class, 'storeDireccion'])->name('sucursal-da.store');
    Route::delete('sucursals/da/delete', [SucursalController::class, 'destroyDireccion'])->name('sucursal-da.destroy');
    Route::post('sucursals/da/habilitar', [SucursalController::class, 'habilitarDireccion'])->name('sucursal-da.habilitar');
    Route::post('sucursals/da/inhabilitar', [SucursalController::class, 'inhabilitarDireccion'])->name('sucursal-da.inhabilitar');

    Route::get('providers', [ProviderController::class, 'index'])->name('voyager.providers.index');

    Route::get('articles', [ArticleController::class, 'index'])->name('voyager.articles.index');
    


    // reporte


    //  Reportes anuales: direcion Administrativa, partidas, detalle de articulos
    Route::get('print/almacen-inventarioAnual-da', [ReportAlmacenController::class, 'directionIncomeSalida'])->name('almacen-inventarioAnual-da.report');
    Route::post('print/almacen-inventarioAnual-da/list', [ReportAlmacenController::class, 'directionIncomeSalidaList'])->name('almacen-direction-income-egress.list');

    Route::get('print/almacen-inventarioAnual-partida', [ReportAlmacenController::class, 'inventarioPartida'])->name('almacen-inventarioAnual-partida.report');
    Route::post('print/almacen-inventarioAnual-partida/list', [ReportAlmacenController::class, 'inventarioPartidaList'])->name('almacen-inventarioAnual-partida.list');

    Route::get('print/almacen-inventarioAnual-detalle', [ReportAlmacenController::class, 'inventarioDetalle'])->name('almacen-inventarioAnual-detalle.report');
    Route::post('print/almacen-inventarioAnual-detalle/list', [ReportAlmacenController::class, 'inventarioDetalleList'])->name('almacen-inventarioAnual-detalle.list');

    // Reporte para generar el stock de ariculos disponible en cada almamacen
    Route::get('print/almacen-article-stock', [ReportAlmacenController::class, 'articleStock'])->name('almacen-article-stock.report');
    Route::post('print/almacen/article/stock/list', [ReportAlmacenController::class, 'articleStockList'])->name('almacen-article-stock.list');


    Route::get('print/almacen-provider-list', [ReportAlmacenController::class, 'provider'])->name('almacen-provider-list.report');
    Route::post('print/almacen/provider/list/list', [ReportAlmacenController::class, 'providerList'])->name('almacen-provider-list.list');





    Route::get('print/almacen-article-inventory', [ReportAlmacenController::class, 'articleInventory'])->name('almacen-article-inventory.report');
    Route::post('print/almacen-article-inventory/list', [ReportAlmacenController::class, 'articleInventoryList'])->name('almacen-article-inventory.list');

   

    Route::get('print/almacen-article-egresado', [ReportAlmacenController::class, 'articleEgresado'])->name('almacen-article-egresado.report');
    Route::post('print/almacen-article-egresado/list', [ReportAlmacenController::class, 'articleEgresadoList'])->name('almacen-article-egresado.list');


    Route::get('print/almacen-article-unidades', [ReportAlmacenController::class, 'articleUnidades'])->name('almacen-article-unidades.report');
    Route::post('print/almacen-article-unidades/list', [ReportAlmacenController::class, 'articleUnidadesList'])->name('almacen-article-unidades.list');




    // Route::get('egresos/browse/pendiente/view/{id?}', [EgressController::class, 'view_pendiente'])->name('egres_view_pendiente');
    // Route::post('egresos/pendiente/entregar', [EgressController::class, 'store_egreso_pendiente'])->name('egreso_store_egreso_pendiente');


    // Route::delete('egres/browse/delete', [EgressController::class, 'destroy'])->name('egres_delete');// en mantenimiento sin funcionamiento 




//     //bandeja
//     Route::resource('bandeja', BandejaController::class);
//     Route::get('bandeja/pendiente/view/{id?}', [BandejaController::class, 'view'])->name('bandeja_pendiente_view');
//     Route::get('bandeja/aprobada/view/{id?}', [BandejaController::class, 'view_aprobada'])->name('bandeja_aprobada_view');
//     Route::post('bandeja/pendiente/aprobarsoicitud', [BandejaController::class, 'aprobar'])->name('bandeja_aprobar_solicitud');
//     Route::post('bandeja/pendiente/rechazarsolicitud', [BandejaController::class, 'rechazo'])->name('bandeja_rechazar_solicitud');

//     Route::get('bandeja/pendiente/view/editar/{id?}', [BandejaController::class, 'view_editar'])->name('view_editar_aprobar_solicitud');
//     Route::post('bandeja/pendiente/view/editar/aprobar', [BandejaController::class, 'editar_aprobar'])->name('store_editar_aprobar_solicitud');




//   //........................  Solicitud para hacer Egresos a jefe de contratacion

//     Route::resource('incomesolicitud', IncomeSolicitudController::class);

//     Route::resource('solicitud', SolicitudController::class);
//     Route::delete('solicitudes/delete', [SolicitudController::class, 'destroy'])->name('solicitudes_delete');
//     Route::post('solicitudes/derivarsolicitud', [SolicitudController::class, 'derivar_proceso'])->name('derivar_proceso_solicitud');
//     Route::get('solicitudes/browse/view/{id?}', [SolicitudController::class, 'view'])->name('solicitudes_view');



//////////////////////////////////////////////////////////////////// SEDEGES ////////////////////////////////////////////////////////////////////////////////////////////////////

//                                                                      DONACION EN LOS ALMACENES

//INCOME
    Route::resource('incomedonor', IncomeDonorController::class);
    Route::delete('incomedonor/browse/delete', [IncomeDonorController::class, 'destroy'])->name('incomedonor_delete');
    Route::get('incomedonor/browse/view/stock/{id?}', [IncomeDonorController::class, 'show_stock'])->name('incomedonor_view_stock');
    Route::post('incomedonor/update', [IncomeDonorController::class, 'update'])->name('incomedonor_update');
    Route::post('incomedonor/delete/archivos', [IncomeDonorController::class, 'destroy_file'])->name('incomedonor_delete_file');

    // Route::get('donacion/browse/view/stock/disponible', [IncomeDonorController::class, 'show_stock_disponible'])->name('incomedonor_view_stock_disponible');


    // Route::get('incomedonor/browse/view/{id?}', [IncomeController::class, 'view_ingreso'])->name('incomedonor_view');
//STOCK VIEW PARA VER EL SEDEGES
    Route::get('incomedonor/stock/view', [DonationStockController::class, 'stock_view'])->name('donation.stock.view');   


//EGRESS
    Route::resource('egressdonor', EgressDonorController::class);  
    Route::get('egressdonor/browse/view/photo/{id?}', [EgressDonorController::class, 'show_photo'])->name('egressdonor_view_photo');



// VISTA PARA SOLICITUDES DE LAS DESCONCENTRADA
    Route::resource('view_stock_donacion', DonacionSolicitudController::class);   








    










    


    //:::::::::::::::::::::::::::::::::::::::::::::::::::::::Route Ajax::::::::::::::::::::::::::::::
    //INCOME
    Route::get('incomes/unida', [IncomeController::class, 'select_sucursal'])->name('select_sucursal');
    Route::get('incomes/selectunidadejecutora/{id?}', [IncomeController::class, 'ajax_unidad_administrativa'])->name('ajax_unidad_administrativa');

    // Route::get('incomes/selectunidadsolicitante/{id?}', [IncomeController::class, 'ajax_unidad_solicitante'])->name('ajax_unidad_solicitante');
    Route::get('incomes/selectarticle/{id?}', [IncomeController::class, 'ajax_article'])->name('ajax_article');
    Route::get('incomes/selectpresentacion/{id?}', [IncomeController::class, 'ajax_presentacion'])->name('ajax_presentacion');


    //SOLICITUD DE EGRESO MEDIANTE VISTA
    Route::get('solicitudes/modalidadcompra/{id?}', [SolicitudController::class, 'ajax_modalidadcompra'])->name('ajax_modalidadcompra'); 
    Route::get('solicitudes/articulosolicitud/{id?}', [SolicitudController::class, 'ajax_articulo'])->name('ajax_solicitudes_articulo');
    Route::get('solicitudes/articuloautollenar/{id?}', [SolicitudController::class, 'ajax_autollenar_articulo'])->name('ajax_autollenar_articulo');


    

    //EGRESS
    Route::get('egress/solicitudcompraunidad/{id?}', [EgressController::class, 'ajax_solicitud_compra'])->name('ajax_solicitud_compra');
    Route::get('egress/selectarticle/modalidadcompra/{id?}', [EgressController::class, 'ajax_egres_select_article'])->name('ajax_egres_select_article');
    Route::get('egress/selectarticle/detalle/{id?}', [EgressController::class, 'ajax_egres_select_article_detalle'])->name('ajax_egres_select_article_detalle');



    //___________________________________________________________________________DONACIONES_______________________________________________________
    Route::get('incomedonor/selectarticle/{id?}', [IncomeDonorController::class, 'ajax_article'])->name('ajax_article_donor');
    Route::get('incomedonor/selectpresentacion/{id?}', [IncomeDonorController::class, 'ajax_presentacion'])->name('ajax_presentacion_donor');
    Route::get('incomedonor/selectcentro/{id?}', [IncomeDonorController::class, 'ajax_centro_acogida'])->name('ajax_centro_acogida');
    Route::get('incomedonor/selectdonante/{id?}', [IncomeDonorController::class, 'ajax_income_donante'])->name('ajax_income_donante');

 //egress
    Route::get('incomedonor/selectarticle/egress/{id?}', [EgressDonorController::class, 'ajax_article'])->name('ajax_disponible_article_donor');
    Route::get('incomedonor/llenar_input/egress/{id?}', [EgressDonorController::class, 'ajax_autollenar_articulo'])->name('ajax_egressdoner_llenarimput');







//NOTIFICACION
    Route::get('notification/donacion/caducidad', [NotificationController::class, 'ajax_notificacion_donacion'])->name('donacion_notificacion');


   



});




// Clear cache
Route::get('/admin/clear-cache', function() {
    Artisan::call('optimize:clear');
    return redirect('/admin/profile')->with(['message' => 'Cache eliminada.', 'alert-type' => 'success']);
})->name('clear.cache');

Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
