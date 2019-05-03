import { Component, OnInit, Directive, AfterViewInit, ElementRef, Input } from '@angular/core';
import * as $ from 'jquery';
import 'jstree';

@Directive({
    selector: '[ngjstree]'
})

export class NgjstreeDirective implements AfterViewInit {

    @Input() options: any;

    tree: any;
    constructor(private el: ElementRef) { }

    ngAfterViewInit() {

        // we've removed npm jquery package and are using jquery n jquery-ui directly by loading them in the index.html
        // If something goes wrong check there
        this.tree = $(this.el.nativeElement).jstree(this.options);
    }
}

// <div id="container" ngjstree >
//     <ul>
//         <li>Root node
//             < ul >
//                 <li>Child node 1 < /li>
//                 < li > Child node 2 < /li>
//             < /ul>
//         < /li>
//     < /ul>
// < /div>

// <div ngjstree[options]="val" >
//     </div>
