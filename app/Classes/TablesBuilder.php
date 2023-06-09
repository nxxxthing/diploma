<?php

namespace App\Classes;

/**
 * Class TablesBuilder
 * Generate table html
 *
 * @package App\Classes\TablesBuilder
 */
class TablesBuilder
{
    protected $tableAttr = [];

    protected $headColumns = [];

    protected $footColumns = [];

    protected $headRowAttr = [];

    protected $footRowAttr = [];

    protected $scriptOptions = [];

    private function __clone()
    {
    }

    /**
     * create new TablesBuilder instance
     *
     * @param array $attr
     * @param array $scriptOptions
     *
     * @return static
     */
    public static function create(array $attr = [], array $scriptOptions = [])
    {
        $ins = new static;
        $ins->tableAttr = $attr;
        $ins->scriptOptions = $scriptOptions;

        return $ins;
    }

    /**
     * Push array of columns here
     * example addHead([ ['text' => 'col1'], ['text' => 'col2', 'attr' => ['class' => 'myClass']], [] ])
     * first - only text
     * second - text and attributes
     * third - empty <td></td>
     *
     * @param string $section
     * @param array $columns
     *
     * @return $this
     */
    protected function addSection($section, array $columns)
    {
        foreach ($columns as $column) {
            $this->addColumnInSection(
                $section,
                ! empty($column['text']) ? $column['text'] : '',
                ! empty($column['attr']) ? $column['attr'] : []
            );
        }

        return $this;
    }

    /**
     * Add attributes to header or footer <tr>
     *
     * @param       $section
     * @param array $attr
     *
     * @return $this
     */
    protected function addAttrToSection($section, array $attr)
    {
        $this->{"{$section}RowAttr"} = $attr;

        return $this;
    }

    /**
     * Add one header or footer column
     *
     * @param       $section
     * @param       $text
     * @param array $attr
     *
     * @return $this
     */
    protected function addColumnInSection($section, $text, array $attr = [])
    {
        $this->{"{$section}Columns"}[] = [
            'attr' => $attr,
            'text' => $text,
        ];

        return $this;
    }

    /**
     * @param bool $initDatatable
     *
     * @return string
     */
    public function make($initDatatable = true)
    {
        $html = '<table ' . $this->attributes($this->tableAttr) . '>';
        $html .= $this->getSection('head');
        $html .= $this->getSection('foot');
        $html .= '</table>';
        if ($initDatatable && $id = $this->tableAttr['id']) {
            $html .= '<script>$(document).ready(function () {
                var opt = {
                    sPaginationType: "bootstrap_alt",
                    bProcessing: !0,
                    bServerSide: !0,
                    ajax: "",
                    sAjaxSource: "",
                    dataSrc: "data",
                    "language": {
                        "lengthMenu": "' . trans('datatables.lengthMenu') . '",
                        "zeroRecords": "' . trans('datatables.zeroRecords') . '",
                        "info": "' . trans('datatables.info') . '",
                        "infoEmpty": "' . trans('datatables.infoEmpty') . '",
                        "search": "' . trans('datatables.search') . '",
                        "infoFiltered": "' . trans('datatables.infoFiltered') . '",
                        "paginate": {
                            "first": "' . trans('datatables.paginate.first') . '",
                            "last": "' . trans('datatables.paginate.last') . '",
                            "next": "' . trans('datatables.paginate.next') . '",
                            "previous": "' . trans('datatables.paginate.previous') . '"
                        }
                    },
                    columnDefs: [{targets: "_all", defaultContent: ""}],
                    fnDrawCallback: function () {
                        return initToggles()
                    }
                };
                var userOpt = ' . json_encode($this->scriptOptions) . ';
                opt = $.extend(opt, userOpt);
                if (typeof datatableСallbacks != "undefined") {
                    opt = $.extend(opt, datatableСallbacks);
                }
                var t = $("#' . $id . '").DataTable(opt);
                t.columns().eq(0).each(function (e) {
                  return $("select", t.column(e).footer()).on("keyup change", function () {
                    if(!$(this).closest(".dataTables_length")) {
                        return t.column(e).search(this.value).draw();
                    }
                  })
                });
              })</script>';
        }

        return $html;
    }


    public function makehtml($initDatatable = true)
    {
        $html = '<table ' . $this->attributes($this->tableAttr) . '>';
        $html .= $this->getSection('head');
        $html .= $this->getSection('foot');
        $html .= '</table>';

        return $html;
    }

    public function makejs($initDatatable = true)
    {
        $js = '';
        if ($initDatatable && $id = $this->tableAttr['id']) {
            $js .= '<script>$(document).ready(function () {
                var opt = {
                   // sPaginationType: "bootstrap_alt",
                    bProcessing: true,
                    bServerSide: true,
                    ajax: "",
                    sAjaxSource: "",

                    order: [[ "position", "asc" ]],
                    ordering: false,
                    pageLength: 10,
                    dataSrc: "data",
                    ' . $this->getReorder() . '
                    "language": {
                        "lengthMenu": "' . trans('datatables.lengthMenu') . '",
                        "zeroRecords": "' . trans('datatables.zeroRecords') . '",
                        "info": "' . trans('datatables.info') . '",
                        "infoEmpty": "' . trans('datatables.infoEmpty') . '",
                        "search": "' . trans('datatables.search') . '",
                        "infoFiltered": "' . trans('datatables.infoFiltered') . '",
                        "paginate": {
                            "first": "' . trans('datatables.paginate.first') . '",
                            "last": "' . trans('datatables.paginate.last') . '",
                            "next": "' . trans('datatables.paginate.next') . '",
                            "previous": "' . trans('datatables.paginate.previous') . '"
                        }
                    },
                    fnDrawCallback: function () {
                        //return initToggles()
                    }
                };

                var userOpt = ' . json_encode($this->scriptOptions) . ';
                opt = $.extend(opt, userOpt);
                if (typeof datatableСallbacks != "undefined") {
                    opt = $.extend(opt, datatableСallbacks);
                }
                var t = $("#' . $id . '").DataTable(opt);
                t.columns().eq(0).each(function (e) {
                  return $("select", t.column(e).footer()).on("keyup change", function () {
                    if(!$(this).closest(".dataTables_length")) {
                        return t.column(e).search(this.value).draw();
                    }
                  })
                });
                '.$this->getReorderScript().'

              })
            </script>';
        }
        return $js;

    }

    public function withReorder($module)
    {
        $this->tableAttr['reorderScript'] = 't.on("row-reorder", function (e, details) {
                    if(details.length) {
                        let rows = [];
                        details.forEach(element => {
                            rows.push({
                                id: t.row(element.node).data().id,
                                position: element.newData
                            });
                        });

                        $.ajax({
                            headers: {"x-csrf-token": _token},
                            method: "POST",
                            url: "'. route('admin.ajax-reorder.'.$module).'",
                            data: { rows }
                        }).done(function () { t.ajax.reload() });
                    }
                });';
        $this->tableAttr['rowReorder'] = 'rowReorder: {
                        selector: "tr td:first-of-type",
                        dataSrc: "position"
                    },
                    columnDefs: [
                        {
                            className: "draggable-cursor",
                            "targets": 0
                        },
                        {
                            visible: false,
                            searchable: false,
                            "targets": "position"
                        }
                    ],';
        return $this;
    }

    private function getReorder()
    {
        return $this->tableAttr['rowReorder'] ?? '';
    }

    private function getReorderScript()
    {
        return $this->tableAttr['reorderScript'] ?? '';
    }


    /**
     * Generate table header
     *
     * @param string $section
     *
     * @return string
     */
    private function getSection($section)
    {
        $html = '';
        $sectionArrayName = "{$section}Columns";
        if (count((array)$this->$sectionArrayName) > 0) {
            $html .= "<t$section><tr {$this->attributes($this->{"{$section}RowAttr"})}>";
            foreach ($this->$sectionArrayName as $col) {
                $html .= "<th {$this->attributes($col['attr'])}>{$col['text']}</th>";
            }
            $html .= "</tr></t$section>";
        }

        return $html;
    }

    /**
     * Build an HTML attribute string from an array.
     *
     * @param array $attributes
     *
     * @return string
     */
    public function attributes($attributes)
    {
        $html = [];

        // For numeric keys we will assume that the key and the value are the same
        // as this will convert HTML attributes such as "required" to a correct
        // form like required="required" instead of using incorrect numerics.
        foreach ((array)$attributes as $key => $value) {
            $element = $this->getAttributeExpression($key, $value);
            if ($element) {
                $html[] = $element;
            }
        }

        return count($html) > 0 ? ' ' . implode(' ', $html) : '';
    }

    /**
     * Build a single attribute expression element.
     *
     * @param string $key
     * @param string $value
     *
     * @return string
     */
    private function getAttributeExpression($key, $value)
    {
        if (is_numeric($key)) {
            $key = $value;
        }
        if (! is_null($value)) {
            return $key . '="' . e($value) . '"';
        }

        return false;
    }

    /**
     * @param       $name
     * @param array $arguments
     *
     * @return \App\Classes\TablesBuilder
     * @throws \Exception
     */
    public function __call($name, array $arguments)
    {
        if (preg_match('/^add(Head|Foot)Column$/', $name, $matches)) {
            return $this->addColumnInSection(strtolower($matches[1]), $arguments[0], $arguments[1]);
        }
        if (preg_match('/^add(Head|Foot)Attr/', $name, $matches)) {
            return $this->addAttrToSection(strtolower($matches[1]), $arguments[0], $arguments[1]);
        }
        if (preg_match('/^add(Head|Foot)/', $name, $matches)) {
            return $this->addSection(strtolower($matches[1]), $arguments[0]);
        }

        throw new \Exception("Method $name not found in class " . __CLASS__);
    }

}
