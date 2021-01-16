from django.shortcuts import render
from django.views.generic import TemplateView
from django.core.files.storage import FileSystemStorage

class Home(TemplateView):
	template_name = 'home.html'

def upload(request):
	context = {}
	if request.method == 'POST':
		uploaded_file = request.FILES['document']
		fs = FileSystemStorage()
		name = fs.save(uploaded_file.name, uploaded_file)
		context['url'] = fs.url(name)
	return render(request, 'dashboard/upload.html', context)

def book_list(request):
	return render(request, 'dashboard/book_list.html')

def upload_book(request):
	return render(request, 'dashboard/upload_book.html')
