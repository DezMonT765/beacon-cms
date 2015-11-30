<?php
namespace app\components;
use app\components\geometry\FileAlreadyExistsException;
use app\components\geometry\FileNotSaveException;
use app\components\geometry\UnsupportedFormatException;

/**
 * Класс, описывающий изображение в формате jpg
 */

class AcImageJPG extends AcImage
{
	/**
	 * Проверяет, поддерживается ли формат jpg
	 *
	 * @return boolean
	 */

	public static function isSupport()
	{
		$gdInfo = parent::getGDinfo();
		$phpVersion = AcImage::getShortPHPVersion();

		if ((float)$phpVersion < 5.3) {
			return (bool)$gdInfo['JPG Support'];
		}

		return (bool)$gdInfo['JPEG Support'] ;
	}

	/**
	 * @param string путь к файлу с изображением
	 * @throws UnsupportedFormatException
	 */

	protected function __construct($filePath)
	{
		if (!self::isSupport())
			throw new UnsupportedFormatException('jpeg');

		parent::__construct($filePath);
		$path = parent::getFilePath();
		parent::setResource(@imagecreatefromjpeg($path));
	}

	/**
	 * @param string путь, по которому будет сохранено изображение
	 * @return AcImage
	 * @throws FileAlreadyExistsException
	 * @throws FileNotSaveException
	 */

	public function save($path)
	{
		return parent::saveAsJPG($path);
	}
}
?>