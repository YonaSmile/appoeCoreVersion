<?php

namespace App;
class File
{
    protected $id;
    protected $userId;
    protected $type;
    protected $typeId;
    protected $name;
    protected $description = null;
    protected $link = null;
    protected $position = null;
    protected $options = null;

    protected $filePath = FILE_DIR_PATH;
    protected $uploadFiles = null;
    protected $dbh = null;

    public function __construct()
    {
        if (is_null($this->dbh)) {
            $this->dbh = \App\DB::connect();
        }
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getTypeId()
    {
        return $this->typeId;
    }

    /**
     * @param mixed $typeId
     */
    public function setTypeId($typeId)
    {
        $this->typeId = $typeId;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param null $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return null
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param null $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * @return null
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param null $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @return null
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param null $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

    /**
     * @return string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * @param string $filePath
     */
    public function setFilePath(string $filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * @return array|null
     */
    public function getUploadFiles()
    {
        return $this->uploadFiles;
    }

    /**
     * @param array|null $uploadFiles
     */
    public function setUploadFiles(array $uploadFiles)
    {
        $this->uploadFiles = $uploadFiles;
    }

    /**
     * @return bool
     */
    public function createTable()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS `appoe_files` (
  					`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                	PRIMARY KEY (`id`),
                	`userId` INT(11) UNSIGNED NOT NULL,
  					`type` VARCHAR(15) NOT NULL,
  					`typeId` INT(11) UNSIGNED NOT NULL,
  					`name` VARCHAR(250) NOT NULL,
  					UNIQUE (`type`, `typeId`, `name`),
  					`description` VARCHAR(250) NULL DEFAULT NULL,
  					`link` VARCHAR(255) NULL DEFAULT NULL,
  					`position` INT(11) NULL DEFAULT NULL,
  					`options` TEXT NULL DEFAULT NULL,
                	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=11;';

        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        $error = $stmt->errorInfo();
        if ($error[0] != '00000') {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function show()
    {

        $sql = 'SELECT * FROM appoe_files WHERE id = :id';

        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();

        $error = $stmt->errorInfo();
        if ($error[0] != '00000') {
            return false;
        } else {
            $row = $stmt->fetch(\PDO::FETCH_OBJ);
            $this->feed($row);

            return true;
        }
    }

    /**
     * @return array|bool
     */
    public function showFiles()
    {
        $sql = 'SELECT * FROM appoe_files WHERE type = :type AND typeId = :typeId ORDER BY position ASC, updated_at DESC';
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':type', $this->type);
        $stmt->bindParam(':typeId', $this->typeId);

        $stmt->execute();
        $error = $stmt->errorInfo();

        if ($error[0] != '00000') {
            return false;
        } else {
            return $stmt->fetchAll(\PDO::FETCH_OBJ);
        }
    }

    /**
     * @return array|bool
     */
    public function showAll()
    {
        $sql = 'SELECT * FROM appoe_files GROUP BY name ORDER BY name ASC';
        $stmt = $this->dbh->prepare($sql);

        $stmt->execute();
        $error = $stmt->errorInfo();

        if ($error[0] != '00000') {
            return false;
        } else {
            return $stmt->fetchAll(\PDO::FETCH_OBJ);
        }
    }

    /**
     *
     * @return bool
     */
    public function save()
    {
        $sql = 'INSERT INTO appoe_files (userId, type, typeId, name, updated_at) VALUES(:userId, :type, :typeId, :name, NOW())';
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':userId', $this->userId);
        $stmt->bindParam(':type', $this->type);
        $stmt->bindParam(':typeId', $this->typeId);
        $stmt->bindParam(':name', $this->name);
        $stmt->execute();

        $this->id = $this->dbh->lastInsertId();
        $error = $stmt->errorInfo();

        if ($error[0] != '00000') {
            return false;
        } else {
            return true;
        }
    }

    /**
     *
     * @return bool
     */
    public function update()
    {
        $sql = 'UPDATE appoe_files SET userId = :userId, typeId = :typeId, description = :description, link = :link, position = :position, options = :options WHERE id = :id';
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':userId', $this->userId);
        $stmt->bindParam(':typeId', $this->typeId);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':link', $this->link);
        $stmt->bindParam(':position', $this->position);
        $stmt->bindParam(':options', $this->options);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        $error = $stmt->errorInfo();

        if ($error[0] != '00000') {
            return false;
        } else {
            return true;
        }
    }

    /**
     *
     * @return bool
     */
    public function changePosition()
    {
        $sql = 'UPDATE appoe_files SET position = :position WHERE id = :id';
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':position', $this->position);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        $error = $stmt->errorInfo();

        if ($error[0] != '00000') {
            return false;
        } else {
            return true;
        }
    }


    /**
     *
     * @return array
     */
    public function upload()
    {
        $returnArr = array(
            'filename' => array(),
            'countUpload' => '',
            'errors' => ''
        );
        $uploadFilesCounter = 0;

        $files = $this->uploadFiles;
        $fileCount = !empty($files['name'][0]) ? count($files['name']) : 0;

        for ($i = 0; $i < $fileCount; $i++) {

            if (!empty($files['name'][$i])) {

                $error = $files['error'][$i];
                if ($error == UPLOAD_ERR_OK) {

                    $tmp_name = $files['tmp_name'][$i];
                    $filename = $this->cleanText($files['name'][$i]);
                    $type = $files['type'][$i];
                    $size = $files['size'][$i];
                    if ($size <= 5621440) {

                        if (
                            $type == 'image/jpeg' || $type == 'image/png' || $type == 'image/gif'
                            || $type == 'image/jpg' || $type == 'image/svg+xml' || $type == 'image/tiff'
                            || $type == 'application/pdf' || $type == 'application/vnd.ms-word'
                            || $type == 'application/vnd.ms-powerpoint' || $type == 'application/vnd.ms-excel'
                            || $type == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                            || $type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                            || $type == 'application/vnd.openxmlformats-officedocument.presentationml.presentation'
                            || $type == 'application/vnd.oasis.opendocument.presentation'
                            || $type == 'application/vnd.oasis.opendocument.spreadsheet'
                            || $type == 'application/vnd.oasis.opendocument.text'
                            || $type == 'text/csv' || $type == 'application/msword' || $type == 'application/json'
                            || $type == 'audio/aac' || $type == 'audio/x-mpegurl' || $type == 'audio/m4a'
                            || $type == 'audio/x-midi' || $type == 'audio/x-ms-wma' || $type == 'audio/mpeg'
                            || $type == 'audio/ogg' || $type == 'audio/wav' || $type == 'audio/x-wav'
                            || $type == 'audio/webm' || $type == 'audio/3gpp'
                            || $type == 'video/x-msvideo' || $type == 'video/mpeg' || $type == 'video/ogg'
                            || $type == 'video/webm' || $type == 'video/3gpp' || $type == 'video/mp4'
                        ) {

                            $this->name = $filename;
                            if (!file_exists($this->filePath . $filename)) {

                                if (move_uploaded_file($tmp_name, $this->filePath . $filename) === false) {
                                    continue;
                                }
                            }

                            array_push($returnArr['filename'], $filename);

                            if (!$this->save()) {
                                continue;
                            }

                            $uploadFilesCounter++;

                        } else {
                            $returnArr['errors'] .= trans('Le format du fichier') . ' ' . $filename . ' ' . trans('n\'est pas reconnu.') . '<br>';
                        }
                    } else {
                        $returnArr['errors'] .= trans('Le fichier') . ' ' . $filename . ' ' . trans('dépasse le poids autorisé.') . '<br>';
                    }
                }
            }
        }
        $returnArr['countUpload'] = $uploadFilesCounter . '/' . $fileCount;
        return $returnArr;
    }

    /**
     * @return bool|mixed
     */
    public function deleteFileByPath()
    {
        $path_file = $this->filePath . $this->name;

        if ($this->countFile() < 2) {
            if (file_exists($path_file)) {
                if (!unlink($path_file)) {
                    return false;
                }
            }
        } else {
            return trans('Ce fichier est rattaché à plusieurs données');
        }

        return true;
    }

    public function deleteFileByName()
    {

        $sql = 'DELETE FROM appoe_files WHERE name = :name';

        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':name', $this->name);
        $stmt->execute();

        $error = $stmt->errorInfo();

        if ($error[0] != '00000') {
            return false;
        } else {
            return true;
        }

    }

    /**
     *
     * @return bool
     */
    public function delete()
    {
        $this->deleteFileByPath();

        $sql = 'DELETE FROM appoe_files WHERE id = :id';

        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();

        $error = $stmt->errorInfo();

        if ($error[0] != '00000') {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @param bool $all
     * @return bool
     */
    public function countFile($all = false)
    {
        $sql = (!$all) ? 'SELECT * FROM appoe_files WHERE name = :name' : 'SELECT * FROM appoe_files';
        $stmt = $this->dbh->prepare($sql);

        if (!$all) {
            $stmt->bindParam(':name', $this->name);
        }

        $stmt->execute();
        $error = $stmt->errorInfo();

        if ($error[0] != '00000') {
            return false;
        } else {
            return $stmt->rowCount();
        }
    }

    /**
     * @param $filename
     *
     * @return string
     */
    public function cleanText($filename)
    {

        $special = array(
            ' ', '\'', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ',
            'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï',
            'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý'
        );

        $normal = array(
            '-', '-', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o',
            'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'E', 'I', 'I', 'I',
            'I', 'N', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y'
        );

        $filename = str_replace($special, $normal, $filename);

        return 'appoe_' . strtoupper($filename);
    }

    /**
     * Feed class attributs
     *
     * @param $data
     */
    public function feed($data)
    {
        foreach ($data as $attribut => $value) {
            $method = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $attribut)));

            if (is_callable(array($this, $method))) {
                $this->$method($value);
            }
        }
    }
}
