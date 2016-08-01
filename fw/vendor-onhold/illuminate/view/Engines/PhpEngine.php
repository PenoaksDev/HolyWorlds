<?php

namespace Illuminate\View\Engines;

use Exception;
use Throwable;
use Symfony\Component\Debug\Exception\FatalThrowableError;

class PhpEngine implements EngineInterface
{
    /**
     * Get the evaluated contents of the view.
     *
     * @param  string  $path
     * @param  array   $data
     * @return string
     */
    public function get($path, array $data = [])
    {
        return $this->evaluatePath($path, $data);
    }

    /**
     * Get the evaluated contents of the view at the given path.
     *
     * @param  string  $__path
     * @param  array   $__data
     * @return string
     */
    protected function evaluatePath($__path, $__data)
    {
        $obLevel = ob_get_level();

        ob_start();

        extract($__data, EXTR_SKIP);

        // We'll evaluate the contents of the view inside a try/catch block so we can
        // flush out any stray output that might get out before an error occurs or
        // an exception is thrown. This prevents any partial views from leaking.
        try {
            //include $__path;

	        // TODO Import these elequently

	        $source = "use \\Illuminate\\Support\\Facades\\Lang;\n";
			$source .= "use \\Illuminate\\Support\\Facades\\Password;\n";
			$source .= "use \\Illuminate\\Support\\Facades\\Artisan;\n";
			$source .= "use \\Illuminate\\Support\\Facades\\Storage;\n";
			$source .= "use \\Illuminate\\Support\\Facades\\Schema;\n";
			$source .= "use \\Illuminate\\Support\\Facades\\Redis;\n";
			$source .= "use \\Illuminate\\Support\\Facades\\Cookie;\n";
			$source .= "use \\Illuminate\\Support\\Facades\\View;\n";
			$source .= "use \\Illuminate\\Support\\Facades\\Request;\n";
			$source .= "use \\Illuminate\\Support\\Facades\\Route;\n";
			$source .= "use \\Illuminate\\Support\\Facades\\URL;\n";
			$source .= "use \\Illuminate\\Support\\Facades\\Bus;\n";
			$source .= "use \\Illuminate\\Support\\Facades\\Auth;\n";
			$source .= "use \\Illuminate\\Support\\Facades\\Event;\n";
			$source .= "use \\Illuminate\\Support\\Facades\\Blade;\n";
			$source .= "use \\Illuminate\\Support\\Facades\\Queue;\n";
			$source .= "use \\Illuminate\\Support\\Facades\\DB;\n";
			$source .= "use \\Illuminate\\Support\\Facades\\Input;\n";
			$source .= "use \\Illuminate\\Support\\Facades\\Session;\n";
			$source .= "use \\Illuminate\\Support\\Facades\\Cache;\n";
			$source .= "use \\Illuminate\\Support\\Facades\\Hash;\n";
			$source .= "use \\Illuminate\\Support\\Facades\\Gate;\n";
			$source .= "use \\Illuminate\\Support\\Facades\\Validator;\n";
			$source .= "use \\Illuminate\\Support\\Facades\\App;\n";
			$source .= "use \\Illuminate\\Support\\Facades\\Mail;\n";
			$source .= "use \\Illuminate\\Support\\Facades\\Response;\n";
			$source .= "use \\Illuminate\\Support\\Facades\\Crypt;\n";
			$source .= "use \\Illuminate\\Support\\Facades\\Config;\n";
			$source .= "use \\Illuminate\\Support\\Facades\\File;\n";
			$source .= "use \\Illuminate\\Support\\Facades\\Facade;\n";
			$source .= "use \\Illuminate\\Support\\Facades\\Redirect;\n";
			$source .= "use \\Illuminate\\Support\\Facades\\Log;\n";

	        $source .= "?>" . file_get_contents( $__path );

	        eval( $source );
        } catch (Exception $e) {
            $this->handleViewException($e, $obLevel);
        } catch (Throwable $e) {
            $this->handleViewException(new FatalThrowableError($e), $obLevel);
        }

        return ltrim(ob_get_clean());
    }

    /**
     * Handle a view exception.
     *
     * @param  \Exception  $e
     * @param  int  $obLevel
     * @return void
     *
     * @throws $e
     */
    protected function handleViewException(Exception $e, $obLevel)
    {
        while (ob_get_level() > $obLevel) {
            // ob_end_clean();
	        echo( ob_get_clean() ); // TODO Output ob in local development env
        }

        throw $e;
    }
}
