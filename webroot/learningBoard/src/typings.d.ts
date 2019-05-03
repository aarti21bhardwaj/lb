/* SystemJS module definition */
declare var module: NodeModule;
interface NodeModule {
  id: string;
}

interface JQuery {
  jstree(container?: any): any;
}

interface JQuery {
  datepicker(options?: any, callback?: Function): any;
}