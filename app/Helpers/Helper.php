<?php

namespace App\Helpers;

use App\Models\Branch;
use App\Models\File;
use App\Models\Language;
use App\Models\MenuItem;
use App\Models\SystemPage;
use App\Models\Translation;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Request;

class Helper{

    public static function getLanguageId()
    {
        $default_language_id = 1;
        $language = Language::where('code', session('locale'))
            ->first();
        return $language->id ?? $default_language_id;
    }
    
    public static function get_languages($id = null)
    {
        if ($id) {
            return Language::find($id);
        }
        return Language::all();
    }

    public static function get_menu($parent_id = null, $menu_id)
    {
        $menu = MenuItem::with('sub_menu')->where(['language_id' => Helper::getLanguageId(), 'parent_id' => $parent_id, 'menu_id' => $menu_id])->orderBy('row_number')->get();
        $menus = [];
        foreach ($menu as $data) {
            switch ($data->type) {
                case 0:
                    $data->url = url($data->value);
                    break;
                case 1:
                    $branch = Branch::with(['languages' => function ($query) {
                        $query->where('language_id', Helper::getLanguageId());
                    }])->find($data->value);

                    $data->url = route('branches', $branch->description->url);
                    break;
                case 2:
                    $system_page = SystemPage::find($data->value);
                    $data->url = route('system.' . session('locale') . '.' . $system_page->route_name);
                    break;
            }
            if ($data->url == "#") {
                $data->url = "javascript:;";
            }
            $menus[] = $data;
        }
        return $menus;
    }


    public static function get_block_title($key)
    {
        $array =  [
            'title' => 'Başlık',
            'sub_title' => 'Alt Başlık',
            'description' => 'Açıklama',
            'btn_1_title' => 'İlk Buton Başlığı',
            'btn_1_project_id' => 'İlk Buton Projesi',
            'image' => 'Görsel',
            'percentage' => 'Yüzde'
        ];
        return $array[$key];
    }

    public static function except_data()
    {
        return ['_token', 'image', 'video', 'file_type', 'has_sub_title', 'has_button', 'has_row_number', 'has_short_description'];
    }
    public static function get_first_letter($text)
    {
        $array = Str::of($text)->explode(' ');
        $char_in = "";
        foreach ($array as $char) {
            $char_in .= $char[0];
        }
        return $char_in;
    }

    public static function date_format($time, $format = 'DD MMMM YYYY H:mm')
    {
        Carbon::setLocale(config('app.locale'));
        $date = Carbon::parse($time);
        return $date->isoFormat($format);
    }

    public static function get_menu_types()
    {
        return [
            0 => ['type' => 'text', 'title' => 'Değişken Url', 'model' => null],
            1 => ['type' => 'select', 'title' => 'Şube', 'model' => 'App\Models\Branch'],
            2 => ['type' => 'select', 'title' => 'Sistem Sayfası', 'model' => 'App\Models\SystemPage']
        ];
    }



    public static function get_all_data($model)
    {
        $data = $model::first();
        if (isset($data->language_id)) {
            return $model::where('language_id',Helper::getLanguageId())->get();
        } else {
            return $model::get();
        }
    }


    public static function log_event($event_name)
    {
        switch ($event_name) {
            case 'created':
                return "yeni kayıt ekleme";
                break;
            case 'updated':
                return "kayıt güncelleme";
                break;
            case 'deleted':
                return "kayıt silme";
                break;
        }
    }

    public static function get_slug()
    {
        return Request::segment(count(Request::segments()));
    }

    public static function get_image($slug)
    {
        $data = File::where('slug', $slug)->first();
        return $data->path;
    }

    public static function get_status_button($data, $id, $column_to_update)
    {
        switch ($data) {
            case 1:
                $name = 'Aktif';
                $color = 'success';
                break;
            case 0:
                $name = 'Pasif';
                $color = 'danger';
                break;
        }

        return '<div class="change_status badge badge-light-' . $color . '" data-id="' . $id . '" data-column="' . $column_to_update . '">' . $name . '</div>';
    }

    public static function get_action_buttons($id, $delete = true, $update = true)
    {
        $deleteBtn = '<a href="javascript:;" data-url="' . route('dashboard.' . self::get_slug() . '.destroy', $id) . '" data-bs-custom-class="tooltip-dark" rel="tooltip" data-bs-toggle="tooltip" data-bs-placement="top" title="Sil" class="btn btn-sm btn-light-danger delete_item me-3"><i class="fas fa-trash fs-4"></i></i></a>';

        $updateBtn = '<a href="' . route('dashboard.' . self::get_slug() . '.edit', $id) . '"  data-bs-custom-class="tooltip-dark" rel="tooltip" data-bs-toggle="tooltip" data-bs-placement="top" title="Düzenle" class="btn btn-sm btn-light-primary"><i class="fas fa-pencil-alt fs-4"></i></a>';


        if (!$delete) $deleteBtn = '';
        if (!$update) $updateBtn = '';

        return $deleteBtn . $updateBtn;
    }

    public static function get_files()
    {
        $files = File::all();
        $new_data = [];
        foreach ($files as $file) {
            if ($file->type == 'video') {
                $file->path = asset('admin/assets/img/video.png');
            }
            $item = [
                'name' => $file->slug,
                'file' => $file->path,
            ];
            array_push($new_data, $item);
        }
        return $new_data;
    }
    public static function check_mime($extension)
    {
        $clear_extensions = ["docx", "doc", "csv", "xlsx", "xls", "ppt", "jpeg", "jpg", "webp", "png", "svg", "pdf", "mp4"];
        return Arr::first($clear_extensions, function ($value) use ($extension) {
            return $value == Str::lower($extension);
        });
    }
    public static function line_by_line($string)
    {
        $array = preg_split("/\r\n|\n|\r/", $string);
        return $array;
    }
    public static function get_branch_locations()
    {
        $data = [];
        $branches = Branch::where('status', 1)->get();
        foreach ($branches as $key => $branch) {
            $data[$key] = [$branch->languages->title, $branch->lat, $branch->lng];
        }
        return json_encode($data);
    }

    public static function save_data_database()
    {
        try {
            DB::beginTransaction();
            $languages = Language::all();
            foreach ($languages as $language) {
                $file = base_path("resources/lang/{$language->code}.json");
                $file_contents = json_decode(file_get_contents($file));
                foreach ($file_contents as $key => $text) {
                    Translation::updateOrCreate(
                        ['key' => $key, 'language_id' => $language->id],
                        [
                            'key' => $key,
                            'text' => $text,
                            'language_id' => $language->id
                        ]
                    );
                }
                DB::commit();
            }
        } catch (Exception $e) {
            dd($e);
            DB::rollback();
        }
    }
    public static function save_data_local()
    {
        try {
            $aranan = "/ __(.*?) /i";
            $system_pages = SystemPage::all();
            $php_dosyalar = glob(base_path('resources/views/frontend/*.blade.php'));
            $homepage = glob(base_path('resources/views/frontend/homepage_block/*.blade.php'));
            $php_dosyalar = array_merge($php_dosyalar, $homepage);
            $franchising = glob(base_path('resources/views/frontend/franchising_block/*.blade.php'));
            $php_dosyalar = array_merge($php_dosyalar, $franchising);
            $layouts = glob(base_path('resources/views/frontend/layouts/*.blade.php'));
            $php_dosyalar = array_merge($php_dosyalar, $layouts);
            $languages = Language::all();
            $control = false;
            foreach ($languages as $language) {
                $file = base_path("resources/lang/{$language->code}.json");
                if (!file_exists($file)) {
                    $tr_file = base_path("resources/lang/tr.json");
                    $file = copy($tr_file, $file);
                }
                $myObj = [];
                $dil_file_contents = file_get_contents($file);
                $dil_file_json = json_decode($dil_file_contents);
                foreach ($php_dosyalar as $php_dosya) {
                    $php_file_contents = file_get_contents($php_dosya);
                    preg_match_all($aranan, $php_file_contents, $sonuc);
                    $sonuclar = $sonuc[1];
                    foreach ($sonuclar as $sonuc) {
                        $sonuc = Str::between($sonuc, "('", "')");
                        foreach ($dil_file_json as $dil_file_key => $struct) {
                            if ($sonuc == $dil_file_key) {
                                $control = true;
                                break;
                            }
                        }
                        if (!$control) {
                            $inp = $dil_file_json;
                            foreach ($inp as $inp_key => $inp_value) {
                                $myObj[$inp_key] = $inp_value;
                            }
                            $myObj[$sonuc] = $sonuc;
                            foreach ($system_pages as $system_page) {
                                $myObj['/' . $system_page->route_name] = '/' . $system_page->route_name;
                            }
                            file_put_contents($file, json_encode($myObj, true));
                        }
                        $control = false;
                    }
                }
            }
        } catch (Exception $e) {
            return $e;
        }
    }
    
}
if (!function_exists('log_info')) {
    function log_info(): array
    {
        return [
            'ip_address' => request()->ip(),
            'user_id' => auth()->check() ? auth()->user()->id : 0,
            'status' => 'information',
        ];
    }
}