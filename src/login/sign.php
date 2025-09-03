<?php
class Signin_submit {
    private array $data = [];
    private string $location = '../index.php';

    public function init(): array
    {
        $req = $this->req('post', 'userid, userpw, redirect');
        $this->location = $req['redirect'] ?? '../index.php';
        return $req;
    }

    public function make(): void
    {
        $req = $this->req('get', 'redirect');
        $this->set('redirect', $req['redirect'] ?? '../index.php');
    }

    public function set(string $key, $val): void {
        $this->data[$key] = $val;
    }

    public function get(string $key) {
        return $this->data[$key] ?? null;
    }

    public function form(): string
    {
        return 'method="post" action="../login/login_ok.php"';
    }

    public function location(): string
    {
        return $this->location;
    }


    private function req(string $method, string $csv): array
    {
        $src = ($method === 'post') ? $_POST : $_GET;
        $out = [];
        foreach (explode(',', $csv) as $field) {
            $k = trim($field);
            $out[$k] = isset($src[$k])
                ? (is_string($src[$k]) ? trim($src[$k]) : $src[$k])
                : null;
        }
        return $out;
    }

}