const orderId = 123;

const orderChannel = window.Echo.private(`order.${orderId}`);

orderChannel.listen('order.status.updated', (eventData) => {
    console.log('Order status updated!', eventData);
    alert(`The orider #${eventData.order.id} is currently in status: ${eventData.order.status}`);
});

orderChannel.subscriptionError((status) => {
    console.error('Failed to subscribe to Order channel:', status);
});

orderChannel.subscribed(() => {
    console.log('Successfully subscribed to Order channel:', orderId);
});

const workerId = 456;
const workerChannel = window.Echo.private(`worker.${workerId}`);

workerChannel.listen('WorkerEvent', (data) => {
    console.log('Worker event:', data);
});