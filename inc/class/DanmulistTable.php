<?php

if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
class DanmuListTable extends WP_List_Table {

	  function __construct(){
        global $status, $page;
                
        //Set parent defaults
        parent::__construct( array(
	            'singular'  => 'id',     //singular name of the listed records
	            'plural'    => 'ids',    //plural name of the listed records
	            'ajax'      => false        //does this table support ajax?
	        ) );
        
       }

	/**
     * 准备要处理的表的项目
     *
     * @return Void
     */
    public function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
        $this->process_bulk_action();

        if(isset($_GET['text'])){
          $data = $this->table_data(trim($_GET['text']));
        }else{
          $data = $this->table_data();
        }
        
        //usort( $data, array(&$this, 'sort_data' ) );
        
		$perPage = 25;
        $currentPage = $this->get_pagenum();
	    $totalItems = count($data);
	    $this->set_pagination_args( array(
	            'total_items' => $totalItems,
	            'per_page'    => $perPage
	    ) );
        $data = array_slice($data,(($currentPage-1)*$perPage),$perPage);
		$this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;


    }
    

    /**sortable
     * 覆盖父列方法。 定义要在列表中使用的列
     *
     * @return Array
     */
    public function get_columns()
    {
        $columns = array(
            'cb'        => '<input type="checkbox" />',
            'id'        => 'ID',
            'post_id'=> __('Post','wpartplayer'),
            'user_id'  =>__('User Id','wpartplayer'),
            'text' => __('Danmuku Content','wpartplayer'),
            'time'  =>__('Danmuku Time','wpartplayer'),
            'date_time' =>__('Danmuku Send time','wpartplayer'),
            'ip' =>'ip',
            // 'caozuos' => '操作'
        );

        return $columns;
    }

    /**
     * 定义隐藏哪些列
     *
     * @return Array
     */
    public function get_hidden_columns()
    {
        return array('user_id');
    }

    /**
     * Define the sortable columns
     *
     * @return Array
     */
    public function get_sortable_columns()
    {
        return array(
        	'id' => array('id', true)
        	);
    }
  
    /**
     * 获取表格数据
     *
     * @return Array
     */
    private function table_data($key='')
    {
    	global $wpdb;
        $data = array();
        $table=$wpdb->prefix.'art_danmuku';

        if(!empty($key)){
            $key='%'.$key.'%';
        	$sql="SELECT * FROM `{$table}` WHERE `text` LIKE '{$key}' ORDER BY `id` DESC";
        }else{
        	$sql="SELECT * FROM `{$table}` ORDER BY `id` DESC";
        }
        $data = $wpdb->get_results( $sql, 'ARRAY_A' ); 

        return $data;
    }

    /**
     * 定义要在表的每列上显示的数据
     *
     * @param  Array $item        Data
     * @param  String $column_name - Current column name
     *
     * @return Mixed
     */
    public function column_default( $item, $column_name )
    {
        switch( $column_name ) {
            case 'id':
            return $item[ $column_name ];
            case 'post_id':
            return '<a href="'.get_permalink($item[ $column_name ]).'" target="_black">'.get_the_title($item[ $column_name ]).'</a>';
            case 'user_id':
            case 'text':
            case 'time':
            case 'date_time':
            case 'ip':
            return $item[ $column_name ];

            default:
                return print_r( $item, true ) ;
        }
    }


    /**
     * Allows you to sort the data by the variables set in the $_GET
     *
     * @return Mixed
     */
    private function sort_data( $a, $b )
    {
        // Set defaults
        $orderby = 'id';
        $order = 'DESC';

        // If orderby is set, use this as the sort column
        if(!empty($_GET['orderby']))
        {
            $orderby = $_GET['orderby'];
        }

        // If order is set use this as the order
        if(!empty($_GET['order']))
        {
            $order = $_GET['order'];
        }

        $result = strcmp( $a[$orderby], $b[$orderby] );

        if($order === 'asc')
        {
            return $result;
        }

        return -$result;
    }


  function column_id($item){
        
       
        //Build row actions
        $actions = array(
            'delete'    => sprintf('<a href="?page=%s&action=%s&id=%s&_wpnonce=%s">%s</a>',$_REQUEST['page'],'del',$item['id'],wp_create_nonce('bulk-' . $this->_args['plural']),__('Delete','wpartplayer'))
        );
        // var_dump($item);
        
        //Return the title contents
        return sprintf('%1$s  %2$s ',
            /*$2%s*/ $item['id'],
            /*$3%s*/ $this->row_actions($actions)
        );
    }

    function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
            /*$2%s*/ $item['id']                //The value of the checkbox should be the record's id
        );
    }

    function get_bulk_actions() {
      $actions = array();
  		if ( MEDIA_TRASH ) {
  			if ( $this->is_trash ) {
  				$actions['untrash'] = __( 'Restore','wpartplayer');
  				$actions['delete']  = __( 'Delete Permanently','wpartplayer');
  			} else {
  				$actions['trash'] = __( 'Move to Trash','wpartplayer');
  			}
  		} else {
  		  $actions['deletes'] = __('Delete','wpartplayer');
  		}

		if ( $this->detached ) {
			$actions['attach'] = __( 'Attach','wpartplayer');
		}

		return $actions;
    }

    function process_bulk_action() {
        //检测何时触发批量操作...
        if( 'del'===$this->current_action() ) {
        	if ( empty( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'bulk-' . $this->_args['plural'] ) ) {
        		wp_die(__( 'Check Failure','wpartplayer'));
        	}
        	if(!empty($_GET['id'])){
        		self::del($_GET['id']);
        	}
        	// var_dump($_POST);
        }if( 'deletes'===$this->current_action() ) {
          if ( empty( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'bulk-' . $this->_args['plural'] ) ) {
            wp_die(__( 'Check Failure','wpartplayer'));
          }
          if(!empty($_POST['id'])){
            foreach ($_POST['id'] as $key => $id) {
              self::del($id);
            }
          }
          // 
        }elseif( 'delall'===$this->current_action() ) {
          if ( empty( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'delall') ) {
            wp_die(__( 'Check Failure','wpartplayer'));
          }
          self::delall();
        }
        
    }


    static function del($id){
    	global $wpdb;
    	$table= $wpdb->prefix.'art_danmuku';
    	$wpdb->delete( $table,array('id'=>$id), array('%d') );
    }
    static function delall(){
      global $wpdb;
      $table= $wpdb->prefix.'art_danmuku';
      $wpdb->query("DELETE FROM `{$table}`");
    }


}
