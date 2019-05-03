import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
  name: 'filter'
})
export class FilterPipe implements PipeTransform {

  // first element of args array is the field to filter and second element is the input search item.
  transform(items: any[], args: any[]): any {
    if (!args[1] || args[1] == '') {
      return items;
    }
    return items.filter(item => item[args[0]].toLowerCase().indexOf(args[1].toLowerCase()) !== -1);
  }

}
