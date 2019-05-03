import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
  name: 'fileExtension'
})
export class FileExtensionPipe implements PipeTransform {

  transform(path: any, args?: any): any {
    // return null;
    console.log(path);
    var fileType = path.split('\\').pop().split('/').pop();
    // var fileType = item.substring(0, item.indexOf('.'));
    fileType = fileType.substr(( Math.max(0, fileType.lastIndexOf(".")) || Infinity) + 1);
    fileType = fileType.toLowerCase();
    console.log('file extension type');

    if(fileType == 'pdf'){
      console.log('pdf file');
      return "webroot/learningBoard/src/assets/img/fileTypes/Pdf-File.png"
    }
    
    if(fileType == 'docx' || fileType == 'doc'){
      return "webroot/learningBoard/src/assets/img/fileTypes/Word-File.png"
    }

    
    if(fileType == 'xls' || fileType == 'xlsx'){
      return "webroot/learningBoard/src/assets/img/fileTypes/Excel-File.png"
      
    }

    if(fileType == 'ppt' || fileType == 'pptx'){
      return "webroot/learningBoard/src/assets/img/fileTypes/PowerPoint-File.png"
      
    }

    if(fileType == 'jpg' || fileType == 'png' || fileType == 'gif' || fileType == 'svg'){
        return 'evidence/'+path;
    }

    return 'webroot/learningBoard/src/assets/img/fileTypes/default-image.png';
  }

}
