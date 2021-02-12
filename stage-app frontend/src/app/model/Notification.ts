export class Notification {
  id: number;
  name: string;
  showIn: string;
  seen: boolean;
  link: string;
  user: any;
  createdAt=new Date();
  updatedAt=new Date();
}
