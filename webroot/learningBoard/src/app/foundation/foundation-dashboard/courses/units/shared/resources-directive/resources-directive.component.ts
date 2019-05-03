import { Component, OnInit, Input } from '@angular/core';
import { ResourcesReflectionsService } from '../../../../../../services/foundation/units/resources-reflections/resources-reflections.service'
import { AppSettings } from '../../../../../../app-settings';


@Component({
  selector: 'app-resources-directive',
  templateUrl: './resources-directive.component.html',
  styleUrls: ['./resources-directive.component.scss']
})
export class ResourcesDirectiveComponent implements OnInit {

  @Input() resources: any;
  @Input() courseId: number;
  @Input() unitId: number;
  @Input() object_name: any = null;
  @Input() object_identifier: any = null;

  resource: boolean; // Controls the display of the inline form for resources
  editingResource: boolean;
  editResourceId: number;
  fileToUpload: File = null; // Placeholder variable for the resource file
  file_path: any;
  file_name: any;
  resource_name: any; // Placeholder variable for the resource name
  url: String = null; // Placeholder variable for resource link
  resource_description: String = null; // Placeholder variable for text resource
  resourceType: any; // Placeholder variable for determining resource type
  collapseBtn :boolean = false; // variable to hide resource form
  editoroptions: any = {
    key: 'DLAHYKAJOEc1HQDUH==',
    imageUpload: false,
    videoUpload: false,
    toolbarButtons: ['fullscreen', 'bold', 'italic', 'underline', 'strikeThrough',
      'subscript', 'superscript', '|', 'fontFamily', 'fontSize', 'color',
      'align',
      'formatOL', 'formatUL', 'outdent', 'indent', 'quote',
      'insertLink', 'insertImage', 'insertVideo', 'embedly',
      'insertTable', '|',
      'specialCharacters',
      'selectAll', 'clearFormatting', '|',
      'spellChecker',
      'undo', 'redo']
  }
  JSObject: Object = Object;
  evnEndpoint: any;

  constructor(
    private rnRService: ResourcesReflectionsService,
  ) {
    this.resource = false; // initialize the resource form. keeps it hidden
  }

  ngOnInit() {
    this.evnEndpoint = AppSettings.ENVIRONMENT;
    this.getResources();
  }

  disableSubmit(){
    if(this.url || this.resource_description){
      return false;
    }else{
      return true;
    }
  }

  typeOfResource(type){
    console.log('type');
    console.log(type);
    this.resourceType = type;
    if(type === 'link'){
      this.resource_description = null;
    }

    if(type === 'file'){
      this.url = null;
      this.resource_description = null;
    }

    if(type === 'description'){
      this.url = null;
    }
  }

  // Callback function when the file input box value changes
  handleFileInput(files: FileList) {
    this.fileToUpload = files.item(0);
  }

  // Function that posts the file to the server
  // This only uploads the file and does not create the resource entity
  uploadFile() {
    this.rnRService.postFile(this.courseId, this.unitId, this.fileToUpload).subscribe(data => {
      this.file_path = data; // taking data in this.file_path temporarily;
      this.file_name = this.file_path.data.file_name;
      this.file_path = this.file_path.data.file_path;
      // TODO: replace the params with variables from the URL in the browser
      this.addResource();
    }, error => {
      console.log(error);
    });
  }

  // This method adds the resource to the server.
  addResource() {
    // Preparing the request object
    this.collapseBtn =  false;
    const data = {
      id: null,
      name: this.resource_name,
      url: null,
      description: null,
      file_path: null,
      file_name: null,
      resource_type: this.resourceType,
      object_name: this.object_name,
      object_identifier: this.object_identifier,
    };

    // Conditionally setting values based on the resource type
    if (this.resourceType === 'link') {
      data.url = this.url
    }
    if (this.resourceType === 'description') {
      data.description = this.resource_description
    }
    if (this.resourceType === 'file') {
      data.file_path = this.file_path
      data.file_name = this.file_name
    }

    // Calling the service
    // TODO: replace the params with variables from the URL in the browser

    if (!this.editingResource) {
      this.rnRService.addUnitResource(this.courseId, this.unitId, data).subscribe(res => {
        this.getResources();
        this.refreshVariables();
      }, err => {
        console.log(err);
      });

    }else {
      data.id = this.editResourceId;
      this.rnRService.editUnitResource(this.courseId, this.unitId, this.editResourceId, data)
      .subscribe(res => {
        this.editingResource = false;
        this.getResources();
        this.refreshVariables();
      }, err => {
        console.log(err);
      });
    }
  }

  getResources() {

    let urlQueryString = '';
    if (this.object_name && this.object_identifier) {
      urlQueryString = '?object_name=' + this.object_name + '&object_identifier=' + this.object_identifier;
    }

    this.rnRService.getUnitResources(this.courseId, this.unitId, urlQueryString)
      .subscribe((response) => {
        this.resources = response;
        this.resources = this.resources.data;
      }, error => {
        console.log(error);
      });
  }

  // Toggles the display of the resource form
  showResource() {
    this.resource = true;
    this.collapseBtn = true;
  }

  // Toggles to hide the resource form
  hideResource() {
    this.resource = false;
    this.collapseBtn = false;
  }

  // Click event function when the save resource button is clicked in the UI
  saveResource() {
    if (this.resourceType == 'file') {
      this.uploadFile();
    } else {
      this.addResource()
    }
  }

  refreshVariables(){
    this.resource = false;
    this.editingResource = false;
    this.editResourceId = null;
    this.resource_description = null;
    this.resourceType = null;
    this.resource_name = null;
    this.url = null;
    this.fileToUpload = null;
    this.file_name = null;
    this.file_path = null;
  }

  deleteResource(id) {
    this.rnRService.deleteResource(this.courseId, this.unitId, id)
    .subscribe( res => {
      this.getResources();
    }, err => {
      console.log(err);
    });
  }

  openEdit(id, resource_source) {
    this.resource = true;
    this.editResourceId = id;
    this.editingResource = true;

    this.resources[resource_source].forEach(resource => {
      if (resource.id === id) {
        this.resource_name = resource.name;
        for (const key in resource) {

          switch (resource['resource_type']) {
            case 'file':
              this.resourceType = 'file';
              this.file_name = resource.file_name;
              this.file_path = resource.file_path;
              break;
          
            case 'description':
              this.resourceType = 'description';
              this.resource_description = resource.description;
              break;

            case 'link':
              this.resourceType = 'link';
              this.url = resource.url;
              break;

            default:
              break;
          }
        }
      }
    });
  }

  openLink(resource) {
    let url: any;
    if (resource.url.indexOf("http://") == 0 || resource.url.indexOf("https://") == 0) {
      /* Do nothing */
      url = resource.url;
    } else {
      url = '//' + resource.url;
    }
    window.open(url, '_new');
  }

}
