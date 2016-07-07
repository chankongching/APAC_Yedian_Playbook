<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
Yii::import('zii.widgets.grid.CGridView');
Yii::import('ext.SimpleHTMLDOM.simple_html_dom');

/**
 * Description of CExGridView
 * When using export mode:
 * please set $template="{summary}\n{items}\n{pager}" to fit your requirement;
 * please set $exportMode="export" to support export mode
 * please set $hideHeader=true to hide header
 * 
 * @author SUNJOY
 */
class CExGridView extends CGridView {

    /**
     * Grid export mode
     * @var string Export mode, default no export , set 'export' to enable export
     */
    public $exportMode = '';
    public $csvDelimiter = ',';    //Columns delimiter
    public $csvEnclosure = '"';    //Enclosure used on data when needed

    /**
     * @var boolean whether to hide the footer pager cells of the grid. When this is true, footer pager cells
     * will not be rendered, which means the grid cannot click to goto page
     * in the footer. Defaults to false.
     * @since 1.1.1
     */
    public $hideFooter = false;

    /**
     * Initializes the grid view.
     * This method will initialize required property values and instantiate {@link columns} objects.
     * Override to avoid output scripts when using export mode
     */
    public function init() {
        // CBaseListView init
        if ($this->dataProvider === null)
            throw new CException(Yii::t('zii', 'The "dataProvider" property cannot be empty.'));

        $this->dataProvider->getData();

        if (isset($this->htmlOptions['id']))
            $this->id = $this->htmlOptions['id'];
        else
            $this->htmlOptions['id'] = $this->id;

        if ($this->enableSorting && $this->dataProvider->getSort() === false)
            $this->enableSorting = false;
        if ($this->enablePagination && $this->dataProvider->getPagination() === false)
            $this->enablePagination = false;

        // override CGridView init
        if ($this->exportMode !== 'export') {
            if (empty($this->updateSelector))
                throw new CException(Yii::t('zii', 'The property updateSelector should be defined.'));
            if (empty($this->filterSelector))
                throw new CException(Yii::t('zii', 'The property filterSelector should be defined.'));

            if (!isset($this->htmlOptions['class']))
                $this->htmlOptions['class'] = 'grid-view';

            if ($this->baseScriptUrl === null)
                $this->baseScriptUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('zii.widgets.assets')) . '/gridview';

            if ($this->cssFile !== false) {
                if ($this->cssFile === null)
                    $this->cssFile = $this->baseScriptUrl . '/styles.css';
                Yii::app()->getClientScript()->registerCssFile($this->cssFile);
            }
        }

        $this->initColumns();
    }

    /**
     * Renders the data items for the grid view.
     * Override to support export mode
     */
    public function renderItems() {
        if ($this->dataProvider->getItemCount() > 0 || $this->showTableOnEmpty) {
            if ($this->exportMode === 'export') {
                ob_start();
                ob_implicit_flush(false);
                $this->renderTableHeader();
                $tableHeader = ob_get_clean();
                $tableHeader = strip_tags($tableHeader, '<th>');

                ob_start();
                ob_implicit_flush(false);
                $this->renderTableBody();
                $tableBody = ob_get_clean();

                if (!$this->hideFooter) {
                    ob_start();
                    ob_implicit_flush(false);
                    $this->renderTableFooter();
                    $tableFooter = ob_get_clean();
                }

                //echo "<table class=\"{$this->itemsCssClass}\">\n";
                // process output to browser
                $out = fopen('php://output', 'w');
                // decode contents
                $simpleHTML = new simple_html_dom();
                // decode header content
                //echo $tableHeader;
                $simpleHTML->load($tableHeader);
                $tableHeaderList = $simpleHTML->find('th');
                if (!empty($tableHeaderList)) {
                    $headerRow = array();
                    foreach ($tableHeaderList as $value) {
                        $headerRow[] = $value->innertext;
                    }
                    fputcsv($out, $headerRow, $this->csvDelimiter, $this->csvEnclosure);
                }
                // decode header content
                //echo $tableBody;
                $tableBody = strip_tags($tableBody, '<tr> <td>');
                $tableBody = str_replace('&nbsp;', '', $tableBody);
                //
                $simpleHTML->clear();
                $simpleHTML->load($tableBody);
                $tableBodyList = $simpleHTML->find('tr');
                if (!empty($tableBodyList)) {
                    foreach ($tableBodyList as $itemRow) {
                        //echo $itemRow->innertext;
                        if (!empty($itemRow)) {
                            $itemRowList = $itemRow->find('td');
                            if (!empty($itemRowList)) {
                                //echo $itemRowList->innertext;
                                $tableBodyRow = array();
                                foreach ($itemRowList as $item) {
                                    $tableBodyRow[] = $item->innertext;
                                }
                                fputcsv($out, $tableBodyRow, $this->csvDelimiter, $this->csvEnclosure);
                            }
                        }
                    }
                }
                $simpleHTML->clear();
                // decode footer
                if (!$this->hideFooter) {
                    //echo $tableFooter;
                }
                //echo "</table>";
                //$simpleHTML->clear();
                // close output
                fclose($out);
            } else {
                echo "<table class=\"{$this->itemsCssClass}\">\n";
                $this->renderTableHeader();
                ob_start();
                $this->renderTableBody();
                $body = ob_get_clean();
                $this->renderTableFooter();
                echo $body; // TFOOT must appear before TBODY according to the standard.
                echo "</table>";
            }
        } else
            $this->renderEmptyText();
    }

    /**
     * Renders the view.
     * This is the main entry of the whole view rendering.
     * Child classes should mainly override {@link renderContent} method.
     * Override to avoid output scripts when using export mode
     */
    public function run() {
        if ($this->exportMode === 'export') {
            //$this->registerClientScript();
            //echo CHtml::openTag($this->tagName, $this->htmlOptions) . "\n";
            $this->renderContent();
            //$this->renderKeys();
            //echo CHtml::closeTag($this->tagName);
        } else {
            parent::run();
        }
    }

}
