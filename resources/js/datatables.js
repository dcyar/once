import DataTable from 'datatables.net-dt';
import "datatables.net-dt/css/dataTables.dataTables.min.css";
import DataTableLangEs from 'datatables.net-plugins/i18n/es-ES.mjs';


$.extend(true, $.fn.dataTable.defaults, {
    language: DataTableLangEs
});