<?php

namespace App\Http\Controllers;

use App\Helpers\Charactor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Route;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Page;
use App\Models\Trainer;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\Admin\HelperController;
use App\Models\ISO3166;
use App\Models\Blog;
use App\Models\Seo;
use App\Models\SeoContent;
use App\Models\Product;
use GeoIp2\Database\Reader;
use App\Models\RelationSeoProductInfo;
use App\Models\Timezone;
use App\Helpers\Url;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class HomeController extends Controller {
    public static function home(Request $request, $language = 'vi'){
        /* ngôn ngữ */
        SettingController::settingLanguage($language);
        /* cache HTML */
        $nameCache              = $language.'home.'.config('main_'.env('APP_NAME').'.cache.extension');
        $pathCache              = Storage::path(config('main_'.env('APP_NAME').'.cache.folderSave')).$nameCache;
        $cacheTime    	        = env('APP_CACHE_TIME') ?? 1800;
        if(file_exists($pathCache)&&$cacheTime>(time() - filectime($pathCache))){
            $xhtml              = file_get_contents($pathCache);
        }else {
            $item               = Page::select('*')
                ->whereHas('seos.infoSeo', function ($query) use ($language) {
                    $query->where('slug', $language);
                })
                ->with('seo', 'seos.infoSeo', 'type')
                ->first();
            /* lấy item seo theo ngôn ngữ được chọn */
            $itemSeo            = [];
            if (!empty($item->seos)) {
                foreach ($item->seos as $s) {
                    if ($s->infoSeo->language == $language) {
                        $itemSeo = $s->infoSeo;
                        break;
                    }
                }
            }
            /* lấy 10 blog hiển thị tạm */
            $params                 = [];
            $params['request_load'] = 10;
            $blogs                  = \App\Http\Controllers\CategoryBlogController::getBlogs($params, $language);
            /* lấy 10 trainer hiển thị tạm */
            $trainers               = Trainer::select('*')
                                        ->skip(0)
                                        ->take(10)
                                        ->get();
            /* Ghi dữ liệu - Xuất kết quả */
            $xhtml                  = view('wallpaper.home.index', compact('item', 'itemSeo', 'trainers', 'blogs', 'language'))->render();
            if(env('APP_CACHE_HTML')==true) Storage::put(config('main_'.env('APP_NAME').'.cache.folderSave').$nameCache, $xhtml);
        }
        echo $xhtml;
    }

    public static function aboutus(Request $request, $language = 'vi'){
        /* ngôn ngữ */
        SettingController::settingLanguage($language);
        // /* cache HTML */
        // $nameCache              = $language.'home.'.config('main_'.env('APP_NAME').'.cache.extension');
        // $pathCache              = Storage::path(config('main_'.env('APP_NAME').'.cache.folderSave')).$nameCache;
        // $cacheTime    	        = env('APP_CACHE_TIME') ?? 1800;
        // if(file_exists($pathCache)&&$cacheTime>(time() - filectime($pathCache))){
        //     $xhtml              = file_get_contents($pathCache);
        // }else {
            $item               = Page::select('*')
                ->whereHas('seos.infoSeo', function ($query) use ($language) {
                    $query->where('language', $language)
                            ->where('slug', 'gioi-thieu');
                })
                ->with('seo', 'seos.infoSeo', 'type')
                ->first();
            /* lấy item seo theo ngôn ngữ được chọn */
            $itemSeo            = [];
            if (!empty($item->seos)) {
                foreach ($item->seos as $s) {
                    if ($s->infoSeo->language == $language) {
                        $itemSeo = $s->infoSeo;
                        break;
                    }
                }
            }
            /* breadcrumb */
            $breadcrumb = Url::buildBreadcrumb($itemSeo->slug_full);
            $xhtml      = view('wallpaper.aboutus.index', compact('item', 'itemSeo', 'language', 'breadcrumb'))->render();
        //     /* Ghi dữ liệu - Xuất kết quả */
        //     if(env('APP_CACHE_HTML')==true) Storage::put(config('main_'.env('APP_NAME').'.cache.folderSave').$nameCache, $xhtml);
        // }
        echo $xhtml;
    }

    public static function contact(Request $request, $language = 'vi'){
        /* ngôn ngữ */
        SettingController::settingLanguage($language);
        // /* cache HTML */
        // $nameCache              = $language.'home.'.config('main_'.env('APP_NAME').'.cache.extension');
        // $pathCache              = Storage::path(config('main_'.env('APP_NAME').'.cache.folderSave')).$nameCache;
        // $cacheTime    	        = env('APP_CACHE_TIME') ?? 1800;
        // if(file_exists($pathCache)&&$cacheTime>(time() - filectime($pathCache))){
        //     $xhtml              = file_get_contents($pathCache);
        // }else {
            $item               = Page::select('*')
                ->whereHas('seos.infoSeo', function ($query) use ($language) {
                    $query->where('language', $language)
                            ->where('slug', 'lien-he');
                })
                ->with('seo', 'seos.infoSeo', 'type')
                ->first();
            /* lấy item seo theo ngôn ngữ được chọn */
            $itemSeo            = [];
            if (!empty($item->seos)) {
                foreach ($item->seos as $s) {
                    if ($s->infoSeo->language == $language) {
                        $itemSeo = $s->infoSeo;
                        break;
                    }
                }
            }
            /* breadcrumb */
            $breadcrumb = Url::buildBreadcrumb($itemSeo->slug_full);
            $xhtml      = view('wallpaper.contact.index', compact('item', 'itemSeo', 'language', 'breadcrumb'))->render();
        //     /* Ghi dữ liệu - Xuất kết quả */
        //     if(env('APP_CACHE_HTML')==true) Storage::put(config('main_'.env('APP_NAME').'.cache.folderSave').$nameCache, $xhtml);
        // }
        echo $xhtml;
    }

    public static function course(Request $request, $language = 'vi'){
        /* ngôn ngữ */
        SettingController::settingLanguage($language);
        // /* cache HTML */
        // $nameCache              = $language.'home.'.config('main_'.env('APP_NAME').'.cache.extension');
        // $pathCache              = Storage::path(config('main_'.env('APP_NAME').'.cache.folderSave')).$nameCache;
        // $cacheTime    	        = env('APP_CACHE_TIME') ?? 1800;
        // if(file_exists($pathCache)&&$cacheTime>(time() - filectime($pathCache))){
        //     $xhtml              = file_get_contents($pathCache);
        // }else {
            $item               = Page::select('*')
                ->whereHas('seos.infoSeo', function ($query) use ($language) {
                    $query->where('language', $language)
                            ->where('slug', 'khoa-hoc');
                })
                ->with('seo', 'seos.infoSeo', 'type')
                ->first();
            /* lấy item seo theo ngôn ngữ được chọn */
            $itemSeo            = [];
            if (!empty($item->seos)) {
                foreach ($item->seos as $s) {
                    if ($s->infoSeo->language == $language) {
                        $itemSeo = $s->infoSeo;
                        break;
                    }
                }
            }
            /* breadcrumb */
            $breadcrumb = Url::buildBreadcrumb($itemSeo->slug_full);
            $xhtml      = view('wallpaper.course.index', compact('item', 'itemSeo', 'language', 'breadcrumb'))->render();
        //     /* Ghi dữ liệu - Xuất kết quả */
        //     if(env('APP_CACHE_HTML')==true) Storage::put(config('main_'.env('APP_NAME').'.cache.folderSave').$nameCache, $xhtml);
        // }
        echo $xhtml;
    }

    public static function timetable(Request $request, $language = 'vi'){
        /* ngôn ngữ */
        SettingController::settingLanguage($language);
        // /* cache HTML */
        // $nameCache              = $language.'home.'.config('main_'.env('APP_NAME').'.cache.extension');
        // $pathCache              = Storage::path(config('main_'.env('APP_NAME').'.cache.folderSave')).$nameCache;
        // $cacheTime    	        = env('APP_CACHE_TIME') ?? 1800;
        // if(file_exists($pathCache)&&$cacheTime>(time() - filectime($pathCache))){
        //     $xhtml              = file_get_contents($pathCache);
        // }else {
            $item               = Page::select('*')
                ->whereHas('seos.infoSeo', function ($query) use ($language) {
                    $query->where('language', $language)
                            ->where('slug', 'lich-hoc');
                })
                ->with('seo', 'seos.infoSeo', 'type')
                ->first();
            /* lấy item seo theo ngôn ngữ được chọn */
            $itemSeo            = [];
            if (!empty($item->seos)) {
                foreach ($item->seos as $s) {
                    if ($s->infoSeo->language == $language) {
                        $itemSeo = $s->infoSeo;
                        break;
                    }
                }
            }
            /* breadcrumb */
            $breadcrumb = Url::buildBreadcrumb($itemSeo->slug_full);
            $xhtml      = view('wallpaper.timetable.index', compact('item', 'itemSeo', 'language', 'breadcrumb'))->render();
        //     /* Ghi dữ liệu - Xuất kết quả */
        //     if(env('APP_CACHE_HTML')==true) Storage::put(config('main_'.env('APP_NAME').'.cache.folderSave').$nameCache, $xhtml);
        // }
        echo $xhtml;
    }

    public static function teacher(Request $request, $language = 'vi'){
        /* ngôn ngữ */
        SettingController::settingLanguage($language);
        // /* cache HTML */
        // $nameCache              = $language.'home.'.config('main_'.env('APP_NAME').'.cache.extension');
        // $pathCache              = Storage::path(config('main_'.env('APP_NAME').'.cache.folderSave')).$nameCache;
        // $cacheTime    	        = env('APP_CACHE_TIME') ?? 1800;
        // if(file_exists($pathCache)&&$cacheTime>(time() - filectime($pathCache))){
        //     $xhtml              = file_get_contents($pathCache);
        // }else {
            $item               = Page::select('*')
                ->whereHas('seos.infoSeo', function ($query) use ($language) {
                    $query->where('language', $language)
                            ->where('slug', 'huan-luyen-vien');
                })
                ->with('seo', 'seos.infoSeo', 'type')
                ->first();
            /* lấy item seo theo ngôn ngữ được chọn */
            $itemSeo            = [];
            if (!empty($item->seos)) {
                foreach ($item->seos as $s) {
                    if ($s->infoSeo->language == $language) {
                        $itemSeo = $s->infoSeo;
                        break;
                    }
                }
            }
            $trainers   = Trainer::all();
            /* breadcrumb */
            $breadcrumb = Url::buildBreadcrumb($itemSeo->slug_full);
            $xhtml      = view('wallpaper.teacher.index', compact('item', 'itemSeo', 'trainers', 'language', 'breadcrumb'))->render();
        //     /* Ghi dữ liệu - Xuất kết quả */
        //     if(env('APP_CACHE_HTML')==true) Storage::put(config('main_'.env('APP_NAME').'.cache.folderSave').$nameCache, $xhtml);
        // }
        echo $xhtml;
    }

    public static function teacherDetail(Request $request, $language = 'vi'){
        /* ngôn ngữ */
        SettingController::settingLanguage($language);
        // /* cache HTML */
        // $nameCache              = $language.'home.'.config('main_'.env('APP_NAME').'.cache.extension');
        // $pathCache              = Storage::path(config('main_'.env('APP_NAME').'.cache.folderSave')).$nameCache;
        // $cacheTime    	        = env('APP_CACHE_TIME') ?? 1800;
        // if(file_exists($pathCache)&&$cacheTime>(time() - filectime($pathCache))){
        //     $xhtml              = file_get_contents($pathCache);
        // }else {
            $item               = Page::select('*')
                ->whereHas('seos.infoSeo', function ($query) use ($language) {
                    $query->where('language', $language)
                            ->where('slug', 'huan-luyen-vien');
                })
                ->with('seo', 'seos.infoSeo', 'type')
                ->first();
            /* lấy item seo theo ngôn ngữ được chọn */
            $itemSeo            = [];
            if (!empty($item->seos)) {
                foreach ($item->seos as $s) {
                    if ($s->infoSeo->language == $language) {
                        $itemSeo = $s->infoSeo;
                        break;
                    }
                }
            }
            /* breadcrumb */
            $breadcrumb = Url::buildBreadcrumb($itemSeo->slug_full);
            $xhtml      = view('wallpaper.teacherDetail.index', compact('item', 'itemSeo', 'language', 'breadcrumb'))->render();
        //     /* Ghi dữ liệu - Xuất kết quả */
        //     if(env('APP_CACHE_HTML')==true) Storage::put(config('main_'.env('APP_NAME').'.cache.folderSave').$nameCache, $xhtml);
        // }
        echo $xhtml;
    }

    public static function qrcode(Request $request){
        // Đường dẫn file trong thư mục storage
        $filePath = Storage::path('public/danh-sach-hlv.xlsx'); // Thay "your-folder" bằng tên thư mục trong storage

        // Đọc file Excel và chuyển thành mảng
        $data = Excel::toArray([], $filePath);

        // Giả sử dữ liệu nằm trong sheet đầu tiên
        $sheet = $data[1];

        // Bỏ qua dòng tiêu đề (nếu có) và chuyển thành mảng huấn luyện viên
        $trainers = [];
        
        foreach ($sheet as $index => $row) {
            if ($index < 4) continue; // Bỏ qua tiêu đề
            $slug       = Charactor::convertStrToUrl(strtolower($row[1]));
            $trainers[] = [
                'name' => $row[1] ?? '',
                'birth_day' => is_numeric($row[2]) ? Date::excelToDateTimeObject($row[2])->format('d/m/Y') : $row[2],
                'cccd' => $row[3] ?? '',   
                'phone' => $row[4] ?? '',   
                'address' => $row[5] ?? '', 
                'link'      => 'https://liendoancutathehinhhcm.com.vn/huan-luyen-vien/'.$slug,
            ];
        }

        for ($i = 0; $i < count($trainers); $i++) {
            $qrLink = $trainers[$i]['link'];
        
            $qrCode = QrCode::encoding('UTF-8')
                ->format('svg')
                ->size(300)
                ->margin(1)
                ->backgroundColor(255, 255, 255)
                ->style('round')
                ->eye('circle')
                ->generate($qrLink);
        
            $trainers[$i]['qrCode'] = "data:image/svg+xml;base64," . base64_encode($qrCode);
        }

        // Trả dữ liệu ra view
        return view('wallpaper.qrcode.index', compact('trainers'));
    }

    public function test(){
        // $link = 'https://liendoancutathehinhhcm.com.vn';

        // // Tạo mã QR Code dạng SVG với tùy chỉnh
        // $qrCode = QrCode::format('svg') // Định dạng SVG
        //     ->size(300) // Kích thước
        //     ->margin(1) // Viền xung quanh
        //     // ->color(0, 138, 192) // Màu mã QR (R, G, B)
        //     ->backgroundColor(255, 255, 255) // Màu nền
        //     ->style('round') // Làm tròn các ô vuông
        //     ->eye('circle') // Làm tròn phần mắt của mã QR
        //     ->merge('https://liendoancutathehinhhcm.storage.googleapis.com/storage/images/logo-liendoancuta-1.webp', 0.3, true)
        //     ->generate($link);

        // // Trả về mã QR Code SVG
        // return response($qrCode)->header('Content-Type', 'image/svg+xml');
                       
    }

    private static function findUniqueElements($arr1, $arr2) {
        // Lọc các phần tử có trong arr1 nhưng không có trong arr2 và ngược lại
        $uniqueInArr1 = array_diff($arr1, $arr2);
        $uniqueInArr2 = array_diff($arr2, $arr1);
        
        // Kết hợp các phần tử không trùng
        return array_merge($uniqueInArr1, $uniqueInArr2);
    }

}