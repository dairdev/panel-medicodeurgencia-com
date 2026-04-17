    /* ================== Authores ================== */
    Route::resource(config('laraadmin.adminRoute') . '/authores', 'AuthoresController');
    Route::get(config('laraadmin.adminRoute') . '/authore_dt_ajax', 'AuthoresController@dtajax');

    /* ================== Paginas ================== */
    Route::resource(config('laraadmin.adminRoute') . '/paginas', 'PaginasController');
    Route::get(config('laraadmin.adminRoute') . '/pagina_dt_ajax', 'PaginasController@dtajax');

    /* ================== Specialties ================== */
    Route::resource(config('laraadmin.adminRoute') . '/specialties', 'SpecialtiesController');
    Route::get(config('laraadmin.adminRoute') . '/specialty_dt_ajax', 'SpecialtiesController@dtajax');

    /* ================== Chapters ================== */
    Route::resource(config('laraadmin.adminRoute') . '/chapters', 'ChaptersController');
    Route::get(config('laraadmin.adminRoute') . '/chapter_dt_ajax', 'ChaptersController@dtajax');

    /* ================== Subchapters ================== */
    Route::resource(config('laraadmin.adminRoute') . '/subchapters', 'SubchaptersController');
    Route::get(config('laraadmin.adminRoute') . '/subchapter_dt_ajax', 'SubchaptersController@dtajax');

    /* ================== Lectores ================== */
    Route::resource(config('laraadmin.adminRoute') . '/lectores', 'LectoresController');
    Route::get(config('laraadmin.adminRoute') . '/lectore_dt_ajax', 'LectoresController@dtajax');

    /* ================== Codigos ================== */
    Route::resource(config('laraadmin.adminRoute') . '/codigos', 'CodigosController');
    Route::get(config('laraadmin.adminRoute') . '/codigo_dt_ajax', 'CodigosController@dtajax');
});
