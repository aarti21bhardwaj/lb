import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
  name: 'deepFilter'
})
export class DeepFilterPipe implements PipeTransform {

  // first element of args array is the field to filter and second element is the input search item.
  transform(items: any[], args: any[]): any {
    if (!args[1] || args[1] == '') {
      return items;
    }
    return items.filter(project => {
      if (project) {
        return !project[args[0]].every(item => {
          return (!item.includes(args[1].toLowerCase()))
        });

      }
    });
  }

}
