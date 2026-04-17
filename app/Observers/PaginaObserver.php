<?php
/***
 * Model generated using LaraAdmin
 * Help: https://laraadmin.com
 * LaraAdmin is open-sourced software licensed under the MIT license.
 * Developed by: Dwij IT Solutions
 * Developer Website: https://dwijitsolutions.com
 */

namespace App\Observers;

use Illuminate\Support\Facades\Log;
use App\Models\LAModule;
use App\Models\LAModuleField;
use Illuminate\Support\Facades\DB;

use App\Models\Pagina;

class PaginaObserver
{
    /**
     * Listen to the Record deleting event.
     *
     * @param  Pagina  $pagina
     * @return void
     */
    public function deleting(Pagina $pagina)
    {
        return LAModule::clearMultiselects('Paginas', $pagina->id);
    }
}
