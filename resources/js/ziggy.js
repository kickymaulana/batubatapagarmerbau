const Ziggy = {"url":"http:\/\/localhost\/batubatapagarmerbau\/public","port":null,"defaults":{},"routes":{"login":{"uri":"login","methods":["GET","HEAD"]},"logout":{"uri":"logout","methods":["POST"]},"dashboard":{"uri":"dashboard","methods":["GET","HEAD"]},"permohonan.store":{"uri":"permohonan","methods":["POST"]},"permohonan.proses-pusat":{"uri":"permohonan\/{id}\/proses-pusat","methods":["POST"],"parameters":["id"]},"permohonan.selesai":{"uri":"permohonan\/{id}\/selesai","methods":["POST"],"parameters":["id"]},"storage.local":{"uri":"storage\/{path}","methods":["GET","HEAD"],"wheres":{"path":".*"},"parameters":["path"]},"storage.local.upload":{"uri":"storage\/{path}","methods":["PUT"],"wheres":{"path":".*"},"parameters":["path"]}}};
if (typeof window !== 'undefined' && typeof window.Ziggy !== 'undefined') {
  Object.assign(Ziggy.routes, window.Ziggy.routes);
}
export { Ziggy };
