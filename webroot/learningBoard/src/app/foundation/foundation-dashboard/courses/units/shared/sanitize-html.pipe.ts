import { Pipe, PipeTransform } from '@angular/core';
import {  DomSanitizer, SafeHtml, SafeStyle, SafeScript, SafeUrl, SafeResourceUrl } from '@angular/platform-browser';
/*
 * Raise the value exponentially
 * Takes an exponent argument that defaults to 1.
 * Usage:
 *   value | exponentialStrength:exponent
 * Example:
 *   {{ 2 | exponentialStrength:10 }}
 *   formats to: 1024
*/

@Pipe({
    name: 'safe'
})

export class SanitizeHtmlPipe implements PipeTransform {
    constructor(public sanitizer: DomSanitizer) {

    }

    transform(style) {
        return this.sanitizer.bypassSecurityTrustHtml(style);
    }
}
