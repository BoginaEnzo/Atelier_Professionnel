using WebApplication1.Entities; // Pour DemoContext
using Microsoft.EntityFrameworkCore; // Pour UseMySQL
using MySql.EntityFrameworkCore.Extensions; // Pour AddEntityFrameworkMySQL
using Microsoft.OpenApi.Models; // Pour la configuration Swagger
using System.Reflection; // Pour l'Assembly (nécessaire pour le nom du fichier XML)

var builder = WebApplication.CreateBuilder(args);

// 1. ENREGISTREMENT DU DBCONTEXT (CORRIGÉ : utilise DemoContext)
builder.Services.AddEntityFrameworkMySQL().AddDbContext<DemoContext>(options =>
{
    // Charge la chaîne de connexion "DefaultConnection" depuis appsettings.json
    options.UseMySQL(builder.Configuration.GetConnectionString("DefaultConnection"));
});


// Add services to the container.

builder.Services.AddControllers();
builder.Services.AddEndpointsApiExplorer();

// 2. CONFIGURATION COMPLÈTE DE SWAGGER (Ajout de la lecture du fichier XML)
builder.Services.AddSwaggerGen(options =>
{
    // Configuration de base pour la documentation
    options.SwaggerDoc("v1", new OpenApiInfo { Version = "v1", Title = "User API" });

    // PRISE EN CHARGE DES COMMENTAIRES XML (nécessite le using System.Reflection)
    var xmlFilename = $"{Assembly.GetExecutingAssembly().GetName().Name}.xml";
    options.IncludeXmlComments(Path.Combine(AppContext.BaseDirectory, xmlFilename));
});

var app = builder.Build();

// Configure the HTTP request pipeline.
if (app.Environment.IsDevelopment())
{
    // Activation de Swagger
    app.UseSwagger();
    app.UseSwaggerUI();
}

app.UseHttpsRedirection();

app.UseAuthorization();

app.MapControllers();

app.Run();