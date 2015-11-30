<?php
namespace app\components;
use app\components\geometry\FileAlreadyExistsException;
use app\components\geometry\FileNotSaveException;
use app\components\geometry\UnsupportedFormatException;

/**
 * Класс, описывающий изображение в формате gif
 */

class AcImageGIF extends AcImage
{

	/**
	 * Проверяет, поддерживается ли формат gif
	 *
	 * @return boolean
	 */

	public static function isSupport()
	{
		$gdInfo = parent::getGDinfo();
		return $gdInfo['GIF Read Support'] && $gdInfo['GIF Create Support'];
	}

	/**
	 * @param string путь к файлу с изображением
	 * @throws UnsupportedFormatException
	 */

	protected function __construct($filePath)
	{
		if (!self::isSupport())
			throw new UnsupportedFormatException('gif');

		parent::__construct($filePath);
		$path = parent::getFilePath();
		parent::setResource(@imagecreatefromgif($path));
	}

	/**
	 * @param string путь, по которому будет сохранено изображение
	 * @return AcImage
	 * @throws FileAlreadyExistsException
	 * @throws FileNotSaveException
	 */

	public function save($path)
	{
		return parent::saveAsGIF($path);
	}
}
?>