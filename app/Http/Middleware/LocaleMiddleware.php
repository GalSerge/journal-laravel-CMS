<?php

namespace App\Http\Middleware;

use Closure;
use App;
use Request;

class LocaleMiddleware
{
    //основной язык, который не должен отображаться в URL
    public static $mainLanguage = 'ru';
    //указываем, какие языки будут использоваться в приложении
    public static $languages = ['en', 'ru'];

    /**
     * Проверяет наличие корректной метки языка в текущем URL
     * Возвращает метку или значение null, если нет метки
     *
     * @return mixed|string|null
     */
    public static function getLocale()
    {
        $url = Request::path();
        $segmentsURL = explode('/',$url);

        //проверяет метку языка - есть ли она среди доступных языков
        if (!empty($segmentsURL[0]) && in_array($segmentsURL[0], self::$languages))
            if ($segmentsURL[0] != self::$mainLanguage)
                return $segmentsURL[0];

        return null;
    }

    /**
     * Устанавливает язык приложения в зависимости от метки языка из URL
     *
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $locale = self::getLocale();

        //если метки нет - устанавливаем основной язык $mainLanguage, т.е. русский
        if($locale)
            App::setLocale($locale);
        else
            App::setLocale(self::$mainLanguage);

        //удаляет метки основного языка из адреса (сейчас основной язык русский)
        $url = $request->getRequestUri();
        $lang_pattern = '/'.self::$mainLanguage;

        if(substr($url, 0, 4 ) === $lang_pattern.'/' || $url === $lang_pattern)
        {
            $url = str_replace($lang_pattern, '', $url);
            return redirect($url);
        }

        return $next($request);
    }
}
