<?php

namespace Modules\Ums\DataTables;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

// models...
use Modules\Ums\Entities\UserResidentialInfo;

class UserResidentialInfoDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                return view('ums::user_residential_info.action', compact('data'))->render();
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param UserResidentialInfo $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(UserResidentialInfo $model)
    {
        // user_residential_info model instance
        $user_residential_info = $model->newQuery();

        // apply joins
        $user_residential_info->join('user_basic_infos', 'user_residential_infos.user_id', 'user_basic_infos.id')
            ->join('users', 'user_residential_infos.user_id', 'users.id');

        // select queries
        $user_residential_info->select([
            'user_residential_infos.id',
            'users.username as username',
            DB::raw('CONCAT(user_basic_infos.first_name," ",user_basic_infos.last_name) as name'),
            'user_residential_infos.present_country',
            'user_residential_infos.present_city',
            'user_residential_infos.present_state',
            'user_residential_infos.present_address_line_1 as present_address',
            'user_residential_infos.updated_at'
        ]);

        // return data
        return $user_residential_info;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('data_table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bflrtip')
            ->orderBy(1)
            ->buttons(
                Button::make('create'),
                Button::make('export'),
                Button::make('print'),
                Button::make('reload')
            )
            ->parameters([
                'pageLength' => 10
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::computed('DT_RowIndex')
                ->title('Sl'),
            Column::make('username')->name('users.username')->hidden(), // alias used
            Column::make('name')->name('user_basic_infos.first_name'), // alias used
            Column::make('name')->name('user_basic_infos.last_name')->hidden(), // alias used
            Column::make('present_country'),
            Column::make('present_city'),
            Column::make('present_state'),
            Column::make('present_address')->name('user_residential_infos.present_address_line_1'), // alias used
            Column::make('updated_at'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'UserResidentialInfo_' . date('YmdHis');
    }
}
