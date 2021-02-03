<?php
class MY_Loader extends CI_Loader {
    public function theme($template_name, $vars = array())
    {
        $this->view('template/header',$vars);
        $this->view('template/style');
        $this->view('template/script');
        $this->view('template/sidebar');
        $this->view($template_name, $vars);
        $this->view('template/modals');
        $this->view('template/footer');
    }
}

?>