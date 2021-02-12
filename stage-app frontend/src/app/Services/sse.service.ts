import {Injectable, NgZone} from '@angular/core';
import {Observable} from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class SSEService {

  constructor(private zone:NgZone) { }

  private getEventSource(url:string):EventSource{
    return new EventSource(url);
  }

  getServerSentEvent(url:string):Observable<any>{
    return new Observable(obser=>{
    const eventSourse = this.getEventSource(url);

    eventSourse.onmessage = event =>{
      this.zone.run(()=> {
        obser.next(event.data);
      });
    };
    eventSourse.onerror = error => {
      this.zone.run(() => {
        obser.next(error);
      });
    };
    });
  }

}
