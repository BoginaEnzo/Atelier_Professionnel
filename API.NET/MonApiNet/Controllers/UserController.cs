using MonApiNet.Entities; // Ajusté pour le namespace de votre projet
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using System.Net;
using System.Collections.Generic;
using System.Threading.Tasks;

// Assurez-vous que le namespace correspond à celui de votre dossier Controllers
namespace MonApiNet.Controllers
{
    [ApiController]
    [Route("api/[controller]")] // Route par défaut : api/user
    public class UserController : ControllerBase
    {
        // Injection de dépendance du DemoContext
        private readonly DemoContext DBContext;

        // Le nom de la variable DBContext est utilisé par convention dans les exemples
        public UserController(DemoContext DBContext)
        {
            this.DBContext = DBContext;
        }

        // --- GET ALL (Récupère tous les utilisateurs) ---
        [HttpGet("GetUsers")]
        public async Task<ActionResult<List<User>>> Get()
        {
            var List = await DBContext.Users.Select(
                s => new User
                {
                    Id = s.Id,
                    FirstName = s.FirstName,
                    LastName = s.LastName,
                    Username = s.Username, // Adapté de Botname à Username
                    Password = s.Password,
                    EnrollmentDate = s.EnrollmentDate
                }
            ).ToListAsync();

            if (List.Count == 0)
            {
                return NotFound();
            }
            else
            {
                return List;
            }
        }

        // --- GET BY ID (Récupère un utilisateur spécifique) ---
        [HttpGet("GetUserById")]
        public async Task<ActionResult<User>> GetUserById(int Id)
        {
            User User = await DBContext.Users.Select(
                s => new User
                {
                    Id = s.Id,
                    FirstName = s.FirstName,
                    LastName = s.LastName,
                    Username = s.Username, // Adapté de Botname à Username
                    Password = s.Password,
                    EnrollmentDate = s.EnrollmentDate
                })
            .FirstOrDefaultAsync(s => s.Id == Id);

            if (User == null)
            {
                return NotFound();
            }
            else
            {
                return User;
            }
        }

        // --- POST (Insère un nouvel utilisateur) ---
        [HttpPost("InsertUser")]
        public async Task<HttpStatusCode> InsertUser(User User)
        {
            var entity = new User()
            {
                Id = User.Id,
                FirstName = User.FirstName,
                LastName = User.LastName,
                Username = User.Username, // Adapté de Botname à Username
                Password = User.Password,
                EnrollmentDate = User.EnrollmentDate
            };
            DBContext.Users.Add(entity);
            await DBContext.SaveChangesAsync();
            return HttpStatusCode.Created;
        }

        // --- PUT (Met à jour un utilisateur existant) ---
        [HttpPut("UpdateUser")]
        public async Task<HttpStatusCode> UpdateUser(User User)
        {
            var entity = await DBContext.Users.FirstOrDefaultAsync(s => s.Id == User.Id);
            if (entity == null) return HttpStatusCode.NotFound; // Gestion basique si non trouvé

            entity.FirstName = User.FirstName;
            entity.LastName = User.LastName;
            entity.Username = User.Username; // Adapté de Botname à Username
            entity.Password = User.Password;
            entity.EnrollmentDate = User.EnrollmentDate;

            await DBContext.SaveChangesAsync();
            return HttpStatusCode.OK;
        }

        // --- DELETE (Supprime un utilisateur) ---
        [HttpDelete("DeleteUser/{Id}")]
        public async Task<HttpStatusCode> DeleteUser(int Id)
        {
            var entity = new User()
            {
                Id = Id
            };
            // On attache et retire l'entité pour une suppression basée uniquement sur l'Id
            DBContext.Users.Attach(entity);
            DBContext.Users.Remove(entity);
            await DBContext.SaveChangesAsync();
            return HttpStatusCode.OK;
        }
    }
}