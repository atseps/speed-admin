export function checkFileType(file: File, accepts: string[]) {
  const newTypes = accepts.join("|");
  // const reg = /\.(jpg|jpeg|png|gif|txt|doc|docx|xls|xlsx|xml)$/i;
  const reg = new RegExp("\\.(" + newTypes + ")$", "i");

  return reg.test(file.name);
}

export function checkImgType(file: File) {
  return isImgTypeByName(file.name);
}

export function isImgTypeByName(name: string) {
  return /\.(jpg|jpeg|png|gif|webp)$/i.test(name);
}

export function getBase64WithFile(file: File) {
  return new Promise<{
    result: string;
    file: File;
  }>((resolve, reject) => {
    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = () => resolve({ result: reader.result as string, file });
    reader.onerror = error => reject(error);
  });
}


/**
 * 根据不同文件类型获取不同文件icon
 * @param fileName
 */
export const getIconByFileType = (fileName: string) => {
  const lastIndex = fileName.lastIndexOf(".");
  const suffix = fileName.slice(lastIndex + 1);
  switch (suffix.toLowerCase()) {
      case "pdf":
          return 'svg:files-icon-pdf';
      case "doc":
      case "docx":
          return 'svg:files-icon-docx';
      case "video":
          return 'svg:files-icon-video';
      case "xls":
      case "csv":
      case "xlsx":
          return 'svg:files-icon-xls';
      case "zip":
      case "rar":
          return 'svg:files-icon-zip';
      default:
          return 'svg:files-icon-other';
  }
};
