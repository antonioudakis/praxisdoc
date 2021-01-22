from django.shortcuts import render, redirect
from django.http import HttpResponse
from django.views.generic import TemplateView
from django.core.files.storage import FileSystemStorage
from .forms import BookForm
from .models import Book
from users.models import StudentFileID

class Home(TemplateView):
	template_name = 'home.html'

def index(request):
	return render(request, 'dashboard/index.html')

def upload(request):
	context = {}
	if request.method == 'POST':
		uploaded_file = request.FILES['document']
		print(uploaded_file)
		print(type(uploaded_file))
		fs = FileSystemStorage()
		name = fs.save(uploaded_file.name, uploaded_file)
		context['url'] = fs.url(name)
	return render(request, 'dashboard/upload.html', context)

def book_list(request):
	books = Book.objects.all()
	return render(request, 'dashboard/book_list.html', {'books': books})

def upload_book(request):
	if request.method == 'POST':
		form = BookForm(request.POST, request.FILES)
		if form.is_valid():
			form.save()
			return redirect('book_list')
	else:
		form = BookForm()
	return render(request, 'dashboard/upload_book.html', {'form': form})

def delete_book(request, pk):
	if request.method == 'POST':
		book = Book.objects.get(pk=pk)
		book.delete()
	return redirect('book_list')


